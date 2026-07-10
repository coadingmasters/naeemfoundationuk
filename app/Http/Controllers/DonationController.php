<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Support\DonationCart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
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

    /** Add a donation line to the basket, then send the donor to checkout. */
    public function add(Request $request): RedirectResponse
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

        return redirect()
            ->route('donate.checkout')
            ->with('success', $data['cause'].' added to your contribution.');
    }

    public function remove(string $id): RedirectResponse
    {
        DonationCart::remove($id);

        return redirect()->route('donate.checkout');
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
        $reference = 'NF-'.strtoupper(Str::random(10));

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
                Donation::create($payload);
            }
        } catch (Throwable $e) {
            // Never block the donor if persistence fails; the payment step still runs.
        }

        // Keep a copy for the payment screen, then empty the basket.
        session(['donation_reference' => $reference, 'donation_total' => $payload['total']]);
        DonationCart::clear();

        return redirect()->route('donate.payment');
    }

    /** Placeholder until the payment gateway is connected. */
    public function payment()
    {
        if (! session('donation_reference')) {
            return redirect()->route('donate.checkout');
        }

        return view('donate.payment', [
            'reference' => session('donation_reference'),
            'total' => session('donation_total'),
        ]);
    }
}
