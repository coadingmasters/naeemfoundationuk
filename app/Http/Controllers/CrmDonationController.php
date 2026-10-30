<?php

namespace App\Http\Controllers;

use App\Mail\DonationReceipt;
use App\Mail\NewDonationAlert;
use App\Models\Donation;
use App\Services\CrmDonations;
use App\Services\CrmException;
use App\Support\Country;
use App\Support\DonationCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Throwable;

/**
 * Donations paid on the CRM's hosted checkout (see config/crm.php).
 *
 * start()    — send the basket to the CRM and redirect the donor to its checkout.
 * complete() — the donor lands back on /donate/thank-you?cart_reference=…&status=…;
 *              we ask the CRM for the real outcome, then update our own donation
 *              record and send our own receipt. Our donations table is kept in
 *              step with the CRM's, exactly as it is for PayPal donations.
 */
class CrmDonationController extends Controller
{
    private const SYMBOLS = ['GBP' => '£', 'USD' => '$', 'CAD' => 'CA$'];

    public function __construct(private CrmDonations $crm) {}

    /** "Donate" on the payment step: create the CRM cart and send the donor to pay. */
    public function start(Request $request): RedirectResponse
    {
        $session = session('donation');

        if (! $session) {
            return redirect()->route('donate.checkout');
        }

        if (DonationCart::isEmpty()) {
            return redirect()
                ->route('donate.checkout')
                ->withErrors(['cart' => 'Your contribution is empty. Please add a donation first.']);
        }

        $currency = (string) Country::get('currency', 'GBP');

        if (! $this->crm->enabledFor($currency)) {
            return redirect()->route('donate.payment');
        }

        $items = DonationCart::items();
        $details = $session['details'] ?? [];
        $reference = $session['reference'];

        try {
            $payload = $this->crm->buildPayload(
                $details,
                $items,
                $reference,
                route('donate.thank-you'), // the CRM appends ?status=…&cart_reference=… to this
                (string) Country::get('name', 'United Kingdom'),
            );
            $cart = $this->crm->createCart($currency, $payload);
        } catch (CrmException $e) {
            Log::error('CRM checkout could not start', ['reference' => $reference, 'error' => $e->getMessage()]);

            return redirect()
                ->route('donate.payment')
                ->withErrors(['crm' => 'We could not start your payment just now. Nothing has been charged — please try again in a moment or contact us.']);
        }

        // Keep our own record in step: pending until the CRM confirms payment.
        try {
            if (Schema::hasTable('donations')) {
                Donation::withoutGlobalScope('region')
                    ->where('reference', $reference)
                    ->first()
                    ?->fill([
                        'frequency' => DonationCart::frequency(),
                        'items' => $items,
                        'currency' => $currency,
                        'subtotal' => DonationCart::subtotal(),
                        'fee' => 0,
                        'total' => DonationCart::subtotal(), // the CRM has no "cover the fee" option
                        'cover_fee' => false,
                        'status' => 'pending',
                        'payment_provider' => 'crm',
                        'payment_id' => $payload['cartReference'],
                    ])
                    ->save();
            }
        } catch (Throwable $e) {
            Log::error('CRM donation record update failed', ['reference' => $reference, 'error' => $e->getMessage()]);
        }

        return redirect()->away($cart['redirectUrl']);
    }

    /** The donor is back from the CRM checkout. */
    public function complete(Request $request): View|RedirectResponse
    {
        $cartReference = (string) $request->query('cart_reference');

        $donation = $cartReference === '' || ! Schema::hasTable('donations') ? null : Donation::withoutGlobalScope('region')
            ->where('payment_provider', 'crm')
            ->where('payment_id', $cartReference)
            ->first();

        if (! $donation) {
            return redirect()->route('donate.checkout');
        }

        // The query string is only a hint; the CRM's own answer decides.
        try {
            $remote = $this->crm->cartStatus((string) $donation->currency, $cartReference);
            $state = CrmDonations::normaliseStatus((string) ($remote['status'] ?? ''));
        } catch (CrmException $e) {
            $state = 'unknown';
        }

        if ($state === 'cancelled' || $state === 'failed') {
            // Our record stays "pending" (the admin shows it as "Not completed").
            return redirect()
                ->route('donate.payment')
                ->withErrors(['crm' => $state === 'cancelled'
                    ? 'Your payment was cancelled, so nothing has been charged. You can try again below.'
                    : 'Your payment did not go through, so nothing has been charged. Please try again below.']);
        }

        $paid = $state === 'paid';

        if ($paid) {
            $this->markPaid($donation);
        }

        // Paid, or accepted and still processing (e.g. a Direct Debit mandate): the
        // donor has done their part, so the basket can go. If we couldn't confirm
        // anything, keep it so nothing is lost.
        if ($paid || $state === 'pending') {
            DonationCart::clear();
            session()->forget(['donation', 'donation_consent']);
        }

        return view('donate.thank-you', $this->viewData($donation, $paid, $state));
    }

    /** Mark paid exactly once, and only the winner of that race sends the emails. */
    private function markPaid(Donation $donation): void
    {
        $won = Donation::withoutGlobalScope('region')
            ->whereKey($donation->getKey())
            ->where('status', '!=', 'paid')
            ->update(['status' => 'paid', 'paid_at' => now()]);

        if ($won !== 1) {
            return;
        }

        $donation->refresh();
        $symbol = self::SYMBOLS[$donation->currency] ?? '£';
        $recurring = $this->recurringPart($donation) > 0;

        try {
            Mail::to($donation->email)->send(new DonationReceipt(
                reference: $donation->reference,
                name: trim($donation->first_name.' '.$donation->last_name),
                items: (array) $donation->items,
                subtotal: (float) $donation->subtotal,
                fee: (float) $donation->fee,
                total: (float) $donation->total,
                giftAid: (bool) $donation->gift_aid,
                currencySymbol: $symbol,
            ));
        } catch (Throwable $e) {
            Log::error('Donation receipt email failed', ['reference' => $donation->reference, 'error' => $e->getMessage()]);
        }

        if ($notify = config('mail.notify')) {
            try {
                Mail::to($notify)->send(new NewDonationAlert(
                    reference: $donation->reference,
                    details: [
                        'first_name' => $donation->first_name,
                        'last_name' => $donation->last_name,
                        'email' => $donation->email,
                        'phone' => $donation->phone,
                        'billing_address' => $donation->billing_address,
                        'city' => $donation->city,
                        'postcode' => $donation->postcode,
                        'gift_aid' => $donation->gift_aid,
                        'on_behalf_of_organisation' => $donation->on_behalf_of_organisation,
                        'organisation_name' => $donation->organisation_name,
                    ],
                    items: (array) $donation->items,
                    subtotal: (float) $donation->subtotal,
                    fee: (float) $donation->fee,
                    total: (float) $donation->total,
                    currency: (string) $donation->currency,
                    currencySymbol: $symbol,
                    paymentId: (string) $donation->payment_id,
                    recurring: $recurring,
                ));
            } catch (Throwable $e) {
                Log::error('Donation owner notification failed', ['reference' => $donation->reference, 'error' => $e->getMessage()]);
            }
        }
    }

    /** Sum of the recurring lines of a stored donation. */
    private function recurringPart(Donation $donation): float
    {
        return round(array_sum(array_map(
            fn ($i) => ($i['frequency'] ?? 'one-off') !== 'one-off' ? (float) $i['amount'] * (int) ($i['qty'] ?? 1) : 0,
            (array) $donation->items,
        )), 2);
    }

    /** @return array<string, mixed> */
    private function viewData(Donation $donation, bool $paid, string $state): array
    {
        $recurringAmount = $this->recurringPart($donation);
        $frequency = collect((array) $donation->items)
            ->pluck('frequency')
            ->first(fn ($f) => in_array($f, ['daily', 'weekly', 'monthly', 'yearly'], true)) ?? 'monthly';

        return [
            'reference' => $donation->reference,
            'total' => (float) $donation->total,
            'recurring' => $recurringAmount > 0,
            'frequency' => $frequency,
            'recurring_amount' => $recurringAmount,
            'one_off' => max(0, round((float) $donation->total - $recurringAmount, 2)),
            'viaCrm' => true,
            // Not yet confirmed paid: still processing, or we couldn't reach the CRM.
            'pending' => ! $paid,
            'unconfirmed' => $state === 'unknown',
        ];
    }
}
