<?php

namespace App\Http\Controllers;

use App\Mail\DonationReceipt;
use App\Mail\NewDonationAlert;
use App\Mail\NewOrderAlert;
use App\Mail\OrderReceipt;
use App\Models\Donation;
use App\Models\Order;
use App\Services\PayPal;
use App\Support\Country;
use App\Support\DonationCart;
use App\Support\ProductCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

/**
 * PayPal Smart Buttons for both the donation basket and the shop.
 *
 * The browser never sends an amount. Every total is recalculated here from the
 * live server-side basket, and the captured amount is checked against it before
 * anything is marked paid.
 */
class PayPalController extends Controller
{
    public function __construct(private PayPal $paypal) {}

    // ---------------------------------------------------------------- donations

    /** Create a PayPal order for the current donation basket. */
    public function donationOrder(Request $request): JsonResponse
    {
        $donation = session('donation');

        if (! $donation || DonationCart::isEmpty()) {
            return response()->json(['error' => 'Your basket has expired. Please start again.'], 422);
        }

        // The donor's fee choice is the only thing the client may influence,
        // and it is applied to a total we calculate ourselves.
        $coverFee = $request->boolean('cover_fee');
        $total = DonationCart::total($coverFee);
        $currency = (string) Country::get('currency', 'GBP');

        try {
            $orderId = $this->paypal->createOrder(
                amount: $total,
                currency: $currency,
                reference: $donation['reference'],
                description: 'Donation to '.config('app.name'),
            );
        } catch (Throwable $e) {
            return response()->json(['error' => 'Could not reach PayPal. Please try again.'], 502);
        }

        // Remember what we asked PayPal to charge so capture can verify it.
        session(['donation.pending_payment' => [
            'order_id' => $orderId,
            'cover_fee' => $coverFee,
            'total' => $total,
            'currency' => $currency,
        ]]);

        // amount + currency let the Apple/Google Pay sheets show the right total.
        return response()->json([
            'id' => $orderId,
            'amount' => number_format($total, 2, '.', ''),
            'currency' => $currency,
        ]);
    }

    /** Capture an approved donation payment and complete the donation. */
    public function donationCapture(Request $request): JsonResponse
    {
        $donation = session('donation');
        $pending = session('donation.pending_payment');

        if (! $donation || ! $pending) {
            return response()->json(['error' => 'Your session has expired. Please start again.'], 422);
        }

        $orderId = (string) $request->input('order_id');

        // Only ever capture the order this session actually created.
        if ($orderId !== ($pending['order_id'] ?? null)) {
            return response()->json(['error' => 'This payment could not be verified.'], 422);
        }

        try {
            $capture = $this->paypal->captureOrder($orderId);
        } catch (Throwable $e) {
            return response()->json(['error' => 'PayPal could not complete the payment.'], 502);
        }

        if ($capture['status'] !== 'COMPLETED') {
            return response()->json(['error' => 'The payment was not completed.'], 422);
        }

        // Defence in depth: PayPal must have taken exactly what we asked for.
        if (! $this->amountMatches($capture, (float) $pending['total'], (string) $pending['currency'])) {
            Log::error('PayPal donation amount mismatch', [
                'reference' => $donation['reference'],
                'expected' => $pending['total'].' '.$pending['currency'],
                'captured' => ($capture['amount'] ?? '?').' '.($capture['currency'] ?? '?'),
            ]);

            return response()->json(['error' => 'The payment amount did not match. Please contact us.'], 422);
        }

        $coverFee = (bool) $pending['cover_fee'];
        $summary = [
            'items' => DonationCart::items(),
            'subtotal' => DonationCart::subtotal(),
            'fee' => $coverFee ? DonationCart::fee() : 0.0,
            'total' => (float) $pending['total'],
        ];

        try {
            if (Schema::hasTable('donations')) {
                Donation::withoutGlobalScope('region')
                    ->where('reference', $donation['reference'])
                    ->first()
                    ?->fill([
                        'items' => $summary['items'],
                        'subtotal' => $summary['subtotal'],
                        'fee' => $summary['fee'],
                        'total' => $summary['total'],
                        'cover_fee' => $coverFee,
                        'status' => 'paid',
                        'payment_provider' => 'paypal',
                        'payment_id' => $capture['capture_id'],
                        'paid_at' => now(),
                    ])
                    ->save();
            }
        } catch (Throwable $e) {
            // The money is taken — never fail the donor on a storage hiccup.
            Log::error('PayPal donation save failed', ['reference' => $donation['reference'], 'error' => $e->getMessage()]);
        }

        $this->sendDonationReceipt($donation, $summary, (string) $pending['currency']);
        $this->notifyOwnerOfDonation($donation, $summary, (string) $pending['currency'], (string) $capture['capture_id'], false);

        // Paid — only now is it safe to empty the basket.
        DonationCart::clear();
        session()->forget(['donation', 'donation_consent']);
        session(['donation_completed' => [
            'reference' => $donation['reference'],
            'total' => $summary['total'],
        ]]);

        return response()->json(['redirect' => route('donate.thank-you')]);
    }

    // ----------------------------------------------------------- subscriptions

    /**
     * Create (or reuse) a monthly PayPal plan for the current gift and return
     * its plan ID. The Smart Button uses this to start a subscription for the
     * exact monthly amount the donor chose.
     */
    public function subscriptionPlan(Request $request): JsonResponse
    {
        $donation = session('donation');

        if (! $donation || DonationCart::isEmpty()) {
            return response()->json(['error' => 'Your basket has expired. Please start again.'], 422);
        }

        // Two or more recurring gifts: one PayPal subscription each, set up one at a time.
        if (DonationCart::splitRecurring()) {
            return $this->splitSubscriptionPlan($request, $donation);
        }

        // One subscription can only repeat on one schedule.
        if (count(DonationCart::recurringFrequencies()) > 1) {
            return response()->json(['error' => 'Your basket has regular gifts on different schedules (e.g. weekly and monthly). Please remove one and give it separately.'], 422);
        }

        $coverFee = $request->boolean('cover_fee');
        // Only the recurring lines repeat; any one-off lines in the same basket
        // are the subscription's setup fee — charged once, today.
        $amount = DonationCart::recurringAmount($coverFee);
        $setupFee = DonationCart::oneOffAmount($coverFee);
        $currency = (string) Country::get('currency', 'GBP');
        $interval = DonationCart::intervalUnit() ?? 'MONTH';

        try {
            $planId = $this->paypal->ensureRecurringPlan($amount, $currency, $interval, $setupFee);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Could not set up recurring giving. Please try again.'], 502);
        }

        session(['donation.pending_subscription' => [
            'plan_id' => $planId,
            'cover_fee' => $coverFee,
            'amount' => $amount,
            'setup_fee' => $setupFee,
            'currency' => $currency,
            'frequency' => DonationCart::frequency(),
        ]]);

        return response()->json([
            'plan_id' => $planId,
            'reference' => $donation['reference'],
        ]);
    }

    /** Record an approved monthly subscription and complete the donation. */
    public function subscriptionRecord(Request $request): JsonResponse
    {
        if (DonationCart::splitRecurring()) {
            return $this->splitSubscriptionRecord($request);
        }

        $donation = session('donation');
        $pending = session('donation.pending_subscription');

        if (! $donation || ! $pending) {
            return response()->json(['error' => 'Your session has expired. Please start again.'], 422);
        }

        $subscriptionId = (string) $request->input('subscription_id');

        if ($subscriptionId === '') {
            return response()->json(['error' => 'This subscription could not be verified.'], 422);
        }

        try {
            $sub = $this->paypal->getSubscription($subscriptionId);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Could not confirm your monthly gift.'], 502);
        }

        // Must belong to the plan we just created, and be live (not cancelled).
        $planId = $sub['plan_id'] ?? null;
        $status = (string) ($sub['status'] ?? '');

        if ($planId !== ($pending['plan_id'] ?? null) || ! in_array($status, ['ACTIVE', 'APPROVAL_PENDING', 'APPROVED'], true)) {
            Log::error('PayPal subscription verify failed', ['reference' => $donation['reference'], 'status' => $status]);

            return response()->json(['error' => 'Your monthly gift could not be verified. Please contact us.'], 422);
        }

        $coverFee = (bool) $pending['cover_fee'];
        $summary = [
            'items' => DonationCart::items(),
            'subtotal' => DonationCart::subtotal(),
            'fee' => $coverFee ? DonationCart::fee() : 0.0,
            // Charged today: the recurring amount plus any one-off lines.
            'total' => round((float) $pending['amount'] + (float) ($pending['setup_fee'] ?? 0), 2),
        ];
        $nextBilling = $sub['billing_info']['next_billing_time'] ?? null;

        try {
            if (Schema::hasTable('donations')) {
                Donation::withoutGlobalScope('region')
                    ->where('reference', $donation['reference'])
                    ->first()
                    ?->fill([
                        'items' => $summary['items'],
                        'subtotal' => $summary['subtotal'],
                        'fee' => $summary['fee'],
                        'total' => $summary['total'],
                        'recurring_amount' => (float) $pending['amount'],
                        'cover_fee' => $coverFee,
                        'frequency' => $pending['frequency'] ?? 'monthly',
                        'status' => 'active',
                        'payment_provider' => 'paypal',
                        'subscription_id' => $subscriptionId,
                        'subscription_status' => $status,
                        'next_billing_at' => $nextBilling ? Carbon::parse($nextBilling) : null,
                        'paid_at' => now(),
                    ])
                    ->save();
            }
        } catch (Throwable $e) {
            Log::error('PayPal subscription save failed', ['reference' => $donation['reference'], 'error' => $e->getMessage()]);
        }

        $this->sendDonationReceipt($donation, $summary, (string) $pending['currency']);
        $this->notifyOwnerOfDonation($donation, $summary, (string) $pending['currency'], $subscriptionId, true);

        DonationCart::clear();
        session()->forget(['donation', 'donation_consent']);
        session(['donation_completed' => [
            'reference' => $donation['reference'],
            'total' => $summary['total'],
            'recurring_amount' => (float) $pending['amount'],
            'one_off' => (float) ($pending['setup_fee'] ?? 0),
            'recurring' => true,
            'frequency' => $pending['frequency'] ?? 'monthly',
        ]]);

        return response()->json(['redirect' => route('donate.thank-you')]);
    }

    // ------------------------------------------- one subscription per recurring gift

    /** Reference for the n-th subscription of a basket: the first keeps the basket's own reference. */
    private function splitReference(string $reference, int $step): string
    {
        return $step === 1 ? $reference : $reference.'-'.$step;
    }

    /** Create (or reuse) the plan for the NEXT recurring gift only. */
    private function splitSubscriptionPlan(Request $request, array $donation): JsonResponse
    {
        $line = DonationCart::nextSplitLine();

        if (! $line || (string) $request->input('line') !== (string) $line['id']) {
            return response()->json(['error' => 'Please set up your regular gifts one at a time, in the order shown.'], 422);
        }

        $done = (array) session('donation.subscribed_lines', []);
        $coverFee = $request->boolean('cover_fee');
        $amount = DonationCart::lineRecurringAmount($line, $coverFee);
        // The basket's one-off gifts are charged once, with the first subscription only.
        $setupFee = $done === [] ? DonationCart::oneOffAmount($coverFee) : 0.0;
        $currency = (string) Country::get('currency', 'GBP');
        $frequency = (string) $line['frequency'];

        try {
            $planId = $this->paypal->ensureRecurringPlan(
                $amount,
                $currency,
                DonationCart::intervalUnitFor($frequency) ?? 'MONTH',
                $setupFee,
            );
        } catch (Throwable $e) {
            return response()->json(['error' => 'Could not set up recurring giving. Please try again.'], 502);
        }

        session(['donation.pending_subscriptions.'.$line['id'] => [
            'plan_id' => $planId,
            'cover_fee' => $coverFee,
            'amount' => $amount,
            'setup_fee' => $setupFee,
            'currency' => $currency,
            'frequency' => $frequency,
        ]]);

        return response()->json([
            'plan_id' => $planId,
            'reference' => $this->splitReference($donation['reference'], count($done) + 1),
        ]);
    }

    /**
     * Record one approved subscription as its own donation (its own reference, its own
     * PayPal subscription id, so PayPal's webhook and the admin's cancel button work on
     * that gift alone). After the last gift the basket is emptied.
     */
    private function splitSubscriptionRecord(Request $request): JsonResponse
    {
        $donation = session('donation');
        $line = DonationCart::nextSplitLine();
        $pending = $line ? session('donation.pending_subscriptions.'.$line['id']) : null;

        if (! $donation || ! $line || ! $pending || (string) $request->input('line') !== (string) $line['id']) {
            return response()->json(['error' => 'Your session has expired. Please start again.'], 422);
        }

        $subscriptionId = (string) $request->input('subscription_id');

        if ($subscriptionId === '') {
            return response()->json(['error' => 'This subscription could not be verified.'], 422);
        }

        try {
            $sub = $this->paypal->getSubscription($subscriptionId);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Could not confirm your regular gift.'], 502);
        }

        $status = (string) ($sub['status'] ?? '');

        if (($sub['plan_id'] ?? null) !== ($pending['plan_id'] ?? null) || ! in_array($status, ['ACTIVE', 'APPROVAL_PENDING', 'APPROVED'], true)) {
            Log::error('PayPal subscription verify failed', ['reference' => $donation['reference'], 'status' => $status]);

            return response()->json(['error' => 'Your regular gift could not be verified. Please contact us.'], 422);
        }

        $done = (array) session('donation.subscribed_lines', []);
        $step = count($done) + 1;
        $reference = $this->splitReference($donation['reference'], $step);
        $coverFee = (bool) $pending['cover_fee'];

        // This gift's own row: the line itself, plus (first row only) the one-off gifts.
        $oneOffItems = $done === [] ? array_values(array_filter(
            DonationCart::items(),
            fn ($i) => ($i['frequency'] ?? 'one-off') === 'one-off',
        )) : [];
        $rowItems = array_merge($oneOffItems, [$line]);
        $subtotal = round(array_sum(array_map(fn ($i) => DonationCart::lineSubtotal($i), $rowItems)), 2);
        $summary = [
            'items' => $rowItems,
            'subtotal' => $subtotal,
            'fee' => $coverFee ? round($subtotal * DonationCart::FEE_RATE, 2) : 0.0,
            // Charged today: the recurring amount plus any one-off gifts.
            'total' => round((float) $pending['amount'] + (float) $pending['setup_fee'], 2),
        ];
        $nextBilling = $sub['billing_info']['next_billing_time'] ?? null;

        try {
            $this->saveSplitDonationRow($donation, $step, $reference, $summary, $pending, $subscriptionId, $status, $nextBilling, $coverFee);
        } catch (Throwable $e) {
            Log::error('PayPal subscription save failed', ['reference' => $reference, 'error' => $e->getMessage()]);
        }

        $rowDonation = ['reference' => $reference] + $donation;
        $this->sendDonationReceipt($rowDonation, $summary, (string) $pending['currency']);
        $this->notifyOwnerOfDonation($rowDonation, $summary, (string) $pending['currency'], $subscriptionId, true);

        $done[$line['id']] = [
            'reference' => $reference,
            'total' => $summary['total'],
            'amount' => (float) $pending['amount'],
            'setup_fee' => (float) $pending['setup_fee'],
            'frequency' => $pending['frequency'],
        ];
        session(['donation.subscribed_lines' => $done]);
        session()->forget('donation.pending_subscriptions.'.$line['id']);

        if (DonationCart::nextSplitLine()) {
            // More gifts to set up: back to the payment step for the next one.
            return response()->json(['redirect' => route('donate.payment')]);
        }

        $gifts = array_values($done);
        DonationCart::clear();
        session()->forget(['donation', 'donation_consent']);
        session(['donation_completed' => [
            'reference' => $donation['reference'],
            'total' => round(array_sum(array_column($gifts, 'total')), 2),
            'recurring_amount' => round(array_sum(array_column($gifts, 'amount')), 2),
            'one_off' => round(array_sum(array_column($gifts, 'setup_fee')), 2),
            'recurring' => true,
            'frequency' => $gifts[0]['frequency'],
            'gifts' => count($gifts),
            'mixed_frequencies' => count(array_unique(array_column($gifts, 'frequency'))) > 1,
        ]]);

        return response()->json(['redirect' => route('donate.thank-you')]);
    }

    /** The first gift fills the donation row made at checkout; later ones get a row of their own. */
    private function saveSplitDonationRow(array $donation, int $step, string $reference, array $summary, array $pending, string $subscriptionId, string $status, ?string $nextBilling, bool $coverFee): void
    {
        if (! Schema::hasTable('donations')) {
            return;
        }

        $fields = [
            'items' => $summary['items'],
            'subtotal' => $summary['subtotal'],
            'fee' => $summary['fee'],
            'total' => $summary['total'],
            'recurring_amount' => (float) $pending['amount'],
            'cover_fee' => $coverFee,
            'frequency' => $pending['frequency'],
            'status' => 'active',
            'payment_provider' => 'paypal',
            'subscription_id' => $subscriptionId,
            'subscription_status' => $status,
            'next_billing_at' => $nextBilling ? Carbon::parse($nextBilling) : null,
            'paid_at' => now(),
        ];

        $base = Donation::withoutGlobalScope('region')->where('reference', $donation['reference'])->first();

        if (! $base) {
            return;
        }

        if ($step === 1) {
            $base->fill($fields)->save();

            return;
        }

        Donation::create($base->only([
            'region', 'first_name', 'last_name', 'email', 'phone', 'billing_address', 'city', 'postcode',
            'gift_aid', 'contact_consent', 'on_behalf_of_organisation', 'organisation_name', 'currency',
        ]) + ['reference' => $reference] + $fields);
    }

    // -------------------------------------------------------------------- shop

    /** Validate the shopper's details, then create a PayPal order for the cart. */
    public function shopOrder(Request $request): JsonResponse
    {
        if (empty(ProductCart::items())) {
            return response()->json(['error' => 'Your cart is empty.'], 422);
        }

        $validator = validator($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:1000'],
            'postcode' => ['required', 'string', 'max:20'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Please complete your delivery details.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $total = ProductCart::subtotal();
        $currency = (string) Country::get('currency', 'GBP');
        $reference = session('shop.reference') ?? 'NF-'.strtoupper(Str::random(8));

        try {
            $orderId = $this->paypal->createOrder(
                amount: $total,
                currency: $currency,
                reference: $reference,
                description: config('app.name').' shop order',
            );
        } catch (Throwable $e) {
            return response()->json(['error' => 'Could not reach PayPal. Please try again.'], 502);
        }

        session([
            'shop.reference' => $reference,
            'shop.details' => $data,
            'shop.pending_payment' => [
                'order_id' => $orderId,
                'total' => $total,
                'currency' => $currency,
            ],
        ]);

        return response()->json([
            'id' => $orderId,
            'amount' => number_format($total, 2, '.', ''),
            'currency' => $currency,
        ]);
    }

    /** Capture an approved shop payment and record the order. */
    public function shopCapture(Request $request): JsonResponse
    {
        $details = session('shop.details');
        $pending = session('shop.pending_payment');
        $reference = session('shop.reference');

        if (! $details || ! $pending || ! $reference) {
            return response()->json(['error' => 'Your session has expired. Please start again.'], 422);
        }

        $orderId = (string) $request->input('order_id');

        if ($orderId !== ($pending['order_id'] ?? null)) {
            return response()->json(['error' => 'This payment could not be verified.'], 422);
        }

        try {
            $capture = $this->paypal->captureOrder($orderId);
        } catch (Throwable $e) {
            return response()->json(['error' => 'PayPal could not complete the payment.'], 502);
        }

        if ($capture['status'] !== 'COMPLETED') {
            return response()->json(['error' => 'The payment was not completed.'], 422);
        }

        if (! $this->amountMatches($capture, (float) $pending['total'], (string) $pending['currency'])) {
            Log::error('PayPal shop amount mismatch', [
                'reference' => $reference,
                'expected' => $pending['total'].' '.$pending['currency'],
                'captured' => ($capture['amount'] ?? '?').' '.($capture['currency'] ?? '?'),
            ]);

            return response()->json(['error' => 'The payment amount did not match. Please contact us.'], 422);
        }

        $symbol = (string) Country::get('symbol', '£');
        $orderItems = array_map(fn ($i) => [
            'name' => $i['product']->name,
            'price' => (float) $i['unit'],
            'qty' => $i['qty'],
            'line' => $i['line'],
        ], ProductCart::items());

        try {
            if (Schema::hasTable('orders')) {
                Order::create([
                    'reference' => $reference,
                    'name' => $details['name'],
                    'email' => $details['email'],
                    'phone' => $details['phone'],
                    'address' => $details['address'],
                    'postcode' => $details['postcode'],
                    'items' => $orderItems,
                    'subtotal' => $pending['total'],
                    'currency' => $pending['currency'],
                    'status' => 'paid',
                    'payment_provider' => 'paypal',
                    'payment_id' => $capture['capture_id'],
                    'paid_at' => now(),
                ]);
            }
        } catch (Throwable $e) {
            Log::error('PayPal shop order save failed', ['reference' => $reference, 'error' => $e->getMessage()]);
        }

        try {
            Mail::to($details['email'])->send(new OrderReceipt(
                reference: $reference,
                name: $details['name'],
                items: $orderItems,
                subtotal: (float) $pending['total'],
                address: $details['address'],
                symbol: $symbol,
            ));
        } catch (Throwable $e) {
            // Never block a paid order on a mail failure.
            Log::error('Shop order receipt email failed', ['reference' => $reference, 'error' => $e->getMessage()]);
        }

        if ($notify = config('mail.notify')) {
            try {
                Mail::to($notify)->send(new NewOrderAlert(
                    reference: $reference,
                    details: $details,
                    items: $orderItems,
                    subtotal: (float) $pending['total'],
                    currency: (string) $pending['currency'],
                    symbol: $symbol,
                    paymentId: (string) $capture['capture_id'],
                ));
            } catch (Throwable $e) {
                Log::error('Shop owner notification failed', ['reference' => $reference, 'error' => $e->getMessage()]);
            }
        }

        ProductCart::clear();
        session()->forget(['shop.details', 'shop.pending_payment', 'shop.reference']);

        // The confirmation screen reads this back.
        session(['order' => [
            'reference' => $reference,
            'name' => $details['name'],
            'total' => (float) $pending['total'],
            'symbol' => $symbol,
        ]]);

        return response()->json(['redirect' => route('shop.order-complete')]);
    }

    // ----------------------------------------------------------------- webhook

    /**
     * PayPal server-to-server notification.
     *
     * Acts as the safety net: if the donor closes the browser before the capture
     * call returns, PayPal still tells us the money was taken.
     */
    public function webhook(Request $request): JsonResponse
    {
        $verified = $this->paypal->verifyWebhook($request->headers->all(), $request->json()->all());

        if (! $verified) {
            Log::warning('PayPal webhook rejected (signature not verified)');

            return response()->json(['status' => 'ignored'], 202);
        }

        $event = (string) $request->input('event_type');

        if ($event === 'PAYMENT.CAPTURE.COMPLETED') {
            $captureId = (string) $request->input('resource.id');
            $reference = (string) $request->input('resource.custom_id');

            $this->markPaidFromWebhook($reference, $captureId);
        }

        // Recurring-gift lifecycle: keep our record's status in step with PayPal.
        if (str_starts_with($event, 'BILLING.SUBSCRIPTION.')) {
            $subId = (string) $request->input('resource.id');
            $status = (string) $request->input('resource.status');
            $nextBilling = $request->input('resource.billing_info.next_billing_time');

            $this->updateSubscriptionFromWebhook($subId, $status, $nextBilling);
        }

        return response()->json(['status' => 'ok']);
    }

    /** Sync a subscription's status (activated / cancelled / suspended) from PayPal. */
    private function updateSubscriptionFromWebhook(string $subscriptionId, string $status, ?string $nextBilling): void
    {
        if ($subscriptionId === '') {
            return;
        }

        try {
            $donation = Donation::withoutGlobalScope('region')->where('subscription_id', $subscriptionId)->first();

            if ($donation) {
                $donation->fill([
                    'subscription_status' => $status ?: $donation->subscription_status,
                    // A cancelled/expired subscription is no longer an active gift.
                    'status' => in_array($status, ['CANCELLED', 'EXPIRED', 'SUSPENDED'], true) ? 'cancelled' : $donation->status,
                    'next_billing_at' => $nextBilling ? Carbon::parse($nextBilling) : $donation->next_billing_at,
                ])->save();
            }
        } catch (Throwable $e) {
            Log::error('PayPal subscription webhook update failed', ['subscription' => $subscriptionId, 'error' => $e->getMessage()]);
        }
    }

    /** Mark a pending row paid if the in-browser capture never got the chance. */
    private function markPaidFromWebhook(string $reference, string $captureId): void
    {
        if ($reference === '' || $captureId === '') {
            return;
        }

        try {
            $donation = Donation::withoutGlobalScope('region')->where('reference', $reference)->first();

            if ($donation && $donation->status !== 'paid') {
                $donation->fill([
                    'status' => 'paid',
                    'payment_provider' => 'paypal',
                    'payment_id' => $captureId,
                    'paid_at' => now(),
                ])->save();
            }
        } catch (Throwable $e) {
            Log::error('PayPal webhook update failed', ['reference' => $reference, 'error' => $e->getMessage()]);
        }
    }

    // ----------------------------------------------------------------- helpers

    /** Did PayPal capture exactly what we asked for? */
    private function amountMatches(array $capture, float $expected, string $currency): bool
    {
        if (($capture['currency'] ?? null) !== $currency) {
            return false;
        }

        // Compare in minor units to sidestep float comparison entirely.
        return (int) round(((float) ($capture['amount'] ?? 0)) * 100) === (int) round($expected * 100);
    }

    private function sendDonationReceipt(array $donation, array $summary, string $currency): void
    {
        $details = $donation['details'] ?? [];
        $email = $details['email'] ?? null;

        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new DonationReceipt(
                reference: $donation['reference'],
                name: trim(($details['first_name'] ?? '').' '.($details['last_name'] ?? '')),
                items: $summary['items'],
                subtotal: (float) $summary['subtotal'],
                fee: (float) $summary['fee'],
                total: (float) $summary['total'],
                giftAid: (bool) ($details['gift_aid'] ?? false),
                currencySymbol: ['GBP' => '£', 'USD' => '$', 'CAD' => 'CA$'][$currency] ?? '£',
            ));
        } catch (Throwable $e) {
            // Mail transport unavailable — the donation still completes.
            Log::error('Donation receipt email failed', ['reference' => $donation['reference'], 'error' => $e->getMessage()]);
        }
    }

    /** Let the charity know a donation has come in (separate from the donor's receipt). */
    private function notifyOwnerOfDonation(array $donation, array $summary, string $currency, string $paymentId, bool $recurring): void
    {
        $notify = config('mail.notify');

        if (! $notify) {
            return;
        }

        try {
            Mail::to($notify)->send(new NewDonationAlert(
                reference: $donation['reference'],
                details: $donation['details'] ?? [],
                items: $summary['items'],
                subtotal: (float) $summary['subtotal'],
                fee: (float) $summary['fee'],
                total: (float) $summary['total'],
                currency: $currency,
                currencySymbol: ['GBP' => '£', 'USD' => '$', 'CAD' => 'CA$'][$currency] ?? '£',
                paymentId: $paymentId,
                recurring: $recurring,
            ));
        } catch (Throwable $e) {
            Log::error('Donation owner notification failed', ['reference' => $donation['reference'], 'error' => $e->getMessage()]);
        }
    }
}
