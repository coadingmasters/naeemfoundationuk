<?php

namespace App\Http\Controllers;

use App\Mail\DonationReceipt;
use App\Mail\OrderReceipt;
use App\Models\Donation;
use App\Models\Order;
use App\Services\PayPal;
use App\Support\Country;
use App\Support\DonationCart;
use App\Support\ProductCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        $coverFee = $request->boolean('cover_fee');
        $amount = DonationCart::total($coverFee);
        $currency = (string) Country::get('currency', 'GBP');
        $interval = DonationCart::intervalUnit() ?? 'MONTH'; // WEEK or MONTH

        try {
            $planId = $this->paypal->ensureRecurringPlan($amount, $currency, $interval);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Could not set up recurring giving. Please try again.'], 502);
        }

        session(['donation.pending_subscription' => [
            'plan_id' => $planId,
            'cover_fee' => $coverFee,
            'amount' => $amount,
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
            'total' => (float) $pending['amount'],
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
                        'cover_fee' => $coverFee,
                        'frequency' => $pending['frequency'] ?? 'monthly',
                        'status' => 'active',
                        'payment_provider' => 'paypal',
                        'subscription_id' => $subscriptionId,
                        'subscription_status' => $status,
                        'next_billing_at' => $nextBilling ? \Illuminate\Support\Carbon::parse($nextBilling) : null,
                        'paid_at' => now(),
                    ])
                    ->save();
            }
        } catch (Throwable $e) {
            Log::error('PayPal subscription save failed', ['reference' => $donation['reference'], 'error' => $e->getMessage()]);
        }

        $this->sendDonationReceipt($donation, $summary, (string) $pending['currency']);

        DonationCart::clear();
        session()->forget(['donation', 'donation_consent']);
        session(['donation_completed' => [
            'reference' => $donation['reference'],
            'total' => $summary['total'],
            'recurring' => true,
            'frequency' => $pending['frequency'] ?? 'monthly',
        ]]);

        return response()->json(['redirect' => route('donate.thank-you')]);
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
                    'next_billing_at' => $nextBilling ? \Illuminate\Support\Carbon::parse($nextBilling) : $donation->next_billing_at,
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
        }
    }
}
