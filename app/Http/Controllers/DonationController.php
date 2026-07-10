<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Support\DonationCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class DonationController extends Controller
{
    /** Optional extras offered on the checkout page. */
    public const ADDONS = [
        ['cause' => 'Orphan Cloth', 'amount' => 5],
        ['cause' => 'Family Meal', 'amount' => 10],
        ['cause' => 'Gaza Children', 'amount' => 20],
        ['cause' => 'Gaza Children Plus', 'amount' => 25],
    ];

    /** Card fields that must never be flashed back into the session. */
    private const SENSITIVE = ['card_number', 'cvc'];

    /**
     * Add a donation line to the basket.
     *
     * AJAX callers get the refreshed basket back so the header mini-cart can
     * update in place; everyone else is redirected to checkout as before.
     */
    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'cause' => ['required', 'string', 'max:120'],
            'amount' => ['required', 'numeric', 'min:1', 'max:1000000'],
            'frequency' => ['nullable', 'in:one-off,monthly'],
            'currency' => ['nullable', 'string', 'max:8'],
            'image' => ['nullable', 'string', 'max:255'],
        ]);

        DonationCart::add([
            'cause' => $data['cause'],
            'amount' => round((float) $data['amount'], 2),
            'qty' => 1,
            'frequency' => $data['frequency'] ?? 'one-off',
            'image' => $data['image'] ?? 'images/changinslives1.jpg',
        ]);

        if ($request->expectsJson()) {
            return $this->cartJson($data['cause'].' added to your basket.');
        }

        return redirect()
            ->route('donate.checkout')
            ->with('success', $data['cause'].' added to your contribution.');
    }

    public function remove(Request $request, string $id): RedirectResponse|JsonResponse
    {
        DonationCart::remove($id);

        if ($request->expectsJson()) {
            return $this->cartJson('Item removed from your basket.');
        }

        return redirect()->route('donate.checkout');
    }

    /** Increase / decrease a basket line. Dropping below 1 removes the line. */
    public function quantity(Request $request, string $id): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:0', 'max:'.DonationCart::MAX_QTY],
        ]);

        DonationCart::setQty($id, (int) $data['qty']);

        if ($request->expectsJson()) {
            return $this->cartJson('Basket updated.');
        }

        return redirect()->route('donate.checkout');
    }

    /** The basket state the header mini-cart needs after every change. */
    private function cartJson(string $message): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'count' => DonationCart::count(),
            'subtotal' => DonationCart::subtotal(),
            'html' => view('partials.cart-body')->render(),
        ]);
    }

    /** The "Complete Contribution" page. */
    public function checkout()
    {
        return view('donate.checkout', [
            'items' => DonationCart::items(),
            'subtotal' => DonationCart::subtotal(),
            'fee' => DonationCart::fee(),
            'addons' => self::ADDONS,
        ]);
    }

    /** Validate donor details, persist the donation, then continue to payment. */
    public function store(Request $request): RedirectResponse
    {
        if (DonationCart::isEmpty()) {
            return redirect()
                ->route('donate.checkout')
                ->withErrors(['cart' => 'Your contribution is empty. Please add a donation first.']);
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'billing_address' => ['required', 'string', 'max:500'],
            'gift_aid' => ['nullable', 'boolean'],
            'on_behalf_of_organisation' => ['nullable', 'boolean'],
            'cover_fee' => ['nullable', 'boolean'],
        ]);

        $coverFee = $request->boolean('cover_fee');

        // Reuse the reference if the donor stepped back to edit their basket,
        // so we update the pending donation rather than creating duplicates.
        $reference = session('donation.reference') ?? 'NF-'.strtoupper(Str::random(10));

        $payload = [
            'reference' => $reference,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'billing_address' => $data['billing_address'],
            'gift_aid' => $request->boolean('gift_aid'),
            'on_behalf_of_organisation' => $request->boolean('on_behalf_of_organisation'),
            'cover_fee' => $coverFee,
            'items' => DonationCart::items(),
            'currency' => 'GBP',
            'subtotal' => DonationCart::subtotal(),
            'fee' => $coverFee ? DonationCart::fee() : 0,
            'total' => DonationCart::total($coverFee),
            'status' => 'pending',
        ];

        try {
            if (Schema::hasTable('donations')) {
                Donation::updateOrCreate(['reference' => $reference], $payload);
            }
        } catch (Throwable $e) {
            // Never block the donor if persistence fails; the payment step still runs.
        }

        // Only the donor's identity and fee choice are carried forward. The basket
        // stays intact and remains the source of truth for the amount charged, so
        // the donor can step back and adjust quantities before paying.
        session(['donation' => [
            'reference' => $reference,
            'donor' => $data['first_name'].' '.$data['last_name'],
            'cover_fee' => $coverFee,
        ]]);

        return redirect()->route('donate.payment');
    }

    /** Payment screen: live basket summary + card details. */
    public function payment()
    {
        $donation = session('donation');

        if (! $donation) {
            return redirect()->route('donate.checkout');
        }

        if (DonationCart::isEmpty()) {
            return redirect()
                ->route('donate.checkout')
                ->withErrors(['cart' => 'Your contribution is empty. Please add a donation first.']);
        }

        return view('donate.payment', [
            'donation' => $donation + $this->summary((bool) ($donation['cover_fee'] ?? false)),
            'months' => $this->months(),
            'years' => range((int) date('Y'), (int) date('Y') + 15),
        ]);
    }

    /** Totals recalculated from the live basket. */
    private function summary(bool $coverFee): array
    {
        return [
            'items' => DonationCart::items(),
            'subtotal' => DonationCart::subtotal(),
            'fee' => $coverFee ? DonationCart::fee() : 0.0,
            'total' => DonationCart::total($coverFee),
        ];
    }

    /**
     * Validate the card and complete the donation.
     *
     * SECURITY: card details are validated for format only. They are never
     * persisted, logged, or flashed back to the session. When a real gateway
     * (Stripe, etc.) is connected, replace the fields with the provider's
     * hosted elements so raw card data never reaches this server at all.
     */
    public function processPayment(Request $request): RedirectResponse
    {
        $donation = session('donation');

        if (! $donation) {
            return redirect()->route('donate.checkout');
        }

        if (DonationCart::isEmpty()) {
            return redirect()
                ->route('donate.checkout')
                ->withErrors(['cart' => 'Your contribution is empty. Please add a donation first.']);
        }

        $validator = Validator::make($request->all(), [
            'card_name' => ['required', 'string', 'max:120'],
            'card_number' => ['required', 'string'],
            'expiry_month' => ['required', 'integer', 'min:1', 'max:12'],
            'expiry_year' => ['required', 'integer', 'min:'.date('Y'), 'max:'.(date('Y') + 20)],
            'cvc' => ['required', 'digits_between:3,4'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $digits = preg_replace('/\D/', '', (string) $request->input('card_number'));

            if (strlen($digits) < 13 || strlen($digits) > 19 || ! $this->passesLuhn($digits)) {
                $validator->errors()->add('card_number', 'Please enter a valid card number.');
            }

            $month = (int) $request->input('expiry_month');
            $year = (int) $request->input('expiry_year');

            if ($year === (int) date('Y') && $month > 0 && $month < (int) date('n')) {
                $validator->errors()->add('expiry_month', 'This card has expired.');
            }
        });

        if ($validator->fails()) {
            // Never flash the card number or CVC back into the session.
            return back()
                ->withErrors($validator)
                ->withInput($request->except(self::SENSITIVE));
        }

        // Recalculate from the live basket so a late edit can never be mischarged.
        $summary = $this->summary((bool) ($donation['cover_fee'] ?? false));

        // --- Gateway charge would happen here, using a tokenised card. ---

        try {
            if (Schema::hasTable('donations')) {
                // Use a model instance so the `items` array cast is applied.
                Donation::where('reference', $donation['reference'])
                    ->first()
                    ?->fill([
                        'items' => $summary['items'],
                        'subtotal' => $summary['subtotal'],
                        'fee' => $summary['fee'],
                        'total' => $summary['total'],
                        'status' => 'paid',
                    ])
                    ->save();
            }
        } catch (Throwable $e) {
            // Payment confirmation should not fail on a persistence error.
        }

        // Payment succeeded — only now is it safe to empty the basket.
        DonationCart::clear();
        session()->forget('donation');
        session(['donation_completed' => [
            'reference' => $donation['reference'],
            'total' => $summary['total'],
        ]]);

        return redirect()->route('donate.thank-you');
    }

    /** Final confirmation screen. */
    public function thankYou()
    {
        $completed = session('donation_completed');

        if (! $completed) {
            return redirect()->route('donate.checkout');
        }

        return view('donate.thank-you', $completed);
    }

    /** Standard Luhn (mod 10) checksum used by all major card schemes. */
    private function passesLuhn(string $number): bool
    {
        $sum = 0;
        $double = false;

        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $digit = (int) $number[$i];

            if ($double) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
            $double = ! $double;
        }

        return $sum % 10 === 0;
    }

    /** @return array<int, string> */
    private function months(): array
    {
        $months = [];

        foreach (range(1, 12) as $m) {
            $months[$m] = str_pad((string) $m, 2, '0', STR_PAD_LEFT).' — '.date('F', mktime(0, 0, 0, $m, 1));
        }

        return $months;
    }
}
