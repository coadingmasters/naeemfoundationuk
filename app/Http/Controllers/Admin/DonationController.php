<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = trim((string) $request->query('q'));

        $donations = Donation::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('reference', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Money received counts one-off payments AND active monthly gifts (their
        // first payment). Totals are grouped by currency — never added together.
        $paid = Donation::whereIn('status', ['paid', 'active']);

        $stats = [
            'raised' => $this->totalsByCurrency((clone $paid)),
            'month' => $this->totalsByCurrency((clone $paid)->where('paid_at', '>=', now()->startOfMonth())),
            'gift_aid' => $this->totalsByCurrency((clone $paid)->where('gift_aid', true)),
            'count' => (clone $paid)->count(),
            'pending' => Donation::where('status', 'pending')->count(),
        ];

        // Where the money is coming from — top cities for completed donations.
        $cities = Donation::whereIn('status', ['paid', 'active'])
            ->whereNotNull('city')
            ->selectRaw('city, currency, COUNT(*) as donations, SUM(total) as amount')
            ->groupBy('city', 'currency')
            ->orderByDesc('amount')
            ->limit(6)
            ->get();

        return view('admin.donations.index', [
            'donations' => $donations,
            'stats' => $stats,
            'cities' => $cities,
            'status' => $status,
            'search' => $search,
        ]);
    }

    /**
     * Sum a donation query per currency.
     *
     * @return array<string, float>  e.g. ['GBP' => 1240.00, 'USD' => 310.00]
     */
    private function totalsByCurrency($query): array
    {
        return $query->selectRaw('currency, SUM(total) as amount')
            ->groupBy('currency')
            ->pluck('amount', 'currency')
            ->map(fn ($v) => (float) $v)
            ->all();
    }

    public function show(Donation $donation)
    {
        return view('admin.donations.show', ['donation' => $donation]);
    }

    /** Cancel an active monthly subscription on PayPal, then mark it cancelled here. */
    public function cancelSubscription(Donation $donation): RedirectResponse
    {
        if (! $donation->subscription_id) {
            return back()->with('error', 'This donation is not a recurring subscription.');
        }

        $ok = app(\App\Services\PayPal::class)->cancelSubscription($donation->subscription_id, 'Cancelled by the charity admin.');

        if (! $ok) {
            return back()->with('error', 'PayPal could not cancel this subscription. Please try again.');
        }

        $donation->update(['subscription_status' => 'CANCELLED', 'status' => 'cancelled']);

        return back()->with('success', 'Subscription cancelled. No further payments will be taken.');
    }

    public function destroy(Donation $donation): RedirectResponse
    {
        $donation->delete();

        return redirect()->route('admin.donations.index')->with('success', 'Donation record deleted.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'donations-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'Reference', 'Status', 'Donor', 'Email', 'Phone', 'Address', 'City', 'Postcode',
                'Region', 'Currency', 'Subtotal', 'Fee covered', 'Total', 'Gift Aid', 'Organisation',
                'Causes', 'Paid via', 'Transaction ID', 'Paid at', 'Created',
            ]);

            Donation::latest()->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $d) {
                    $causes = collect($d->items ?? [])
                        ->map(fn ($i) => ($i['qty'] ?? 1).'x '.($i['cause'] ?? '').' @'.($i['amount'] ?? 0))
                        ->implode('; ');

                    fputcsv($out, [
                        $d->reference,
                        $d->status,
                        trim($d->first_name.' '.$d->last_name),
                        $d->email,
                        $d->phone,
                        $d->billing_address,
                        $d->city,
                        $d->postcode,
                        $d->region,
                        $d->currency,
                        $d->subtotal,
                        $d->fee,
                        $d->total,
                        $d->gift_aid ? 'Yes' : 'No',
                        $d->organisation_name,
                        $causes,
                        $d->payment_provider,
                        $d->payment_id,
                        $d->paid_at?->format('Y-m-d H:i'),
                        $d->created_at?->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
