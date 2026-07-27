@extends('admin.layouts.app')

@section('title', 'Donations')
@section('heading', 'Donations')
@section('subheading', 'Every donation taken through the website, with donor and payment details.')

@section('actions')
    @if ($donations->total() > 0)
        <a href="{{ route('admin.donations.export') }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-navy transition hover:border-brand hover:text-brand">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Export CSV
        </a>
    @endif
@endsection

@section('content')

    {{-- Money raised at a glance --}}
    @php
        $symbols = ['GBP' => '£', 'USD' => '$', 'CAD' => 'CA$'];
        // Amounts are kept per currency — GBP, USD and CAD are never added together.
        $fmt = function (array $totals) use ($symbols) {
            if (empty($totals)) {
                return ['0.00'];
            }

            return collect($totals)
                ->map(fn ($amount, $code) => ($symbols[$code] ?? '').number_format($amount, 2))
                ->values()
                ->all();
        };

        $cards = [
            ['label' => 'Total raised', 'values' => $fmt($stats['raised']), 'sub' => $stats['count'].' completed donations', 'tone' => 'brand'],
            ['label' => 'This month', 'values' => $fmt($stats['month']), 'sub' => now()->format('F Y'), 'tone' => 'green'],
            ['label' => 'Gift Aid eligible', 'values' => $fmt($stats['gift_aid']), 'sub' => 'Claim +25% on this', 'tone' => 'navy'],
            ['label' => 'Not completed', 'values' => [number_format($stats['pending'])], 'sub' => 'Started but never paid', 'tone' => 'amber'],
        ];
        $tones = [
            'brand' => 'bg-brand/10 text-brand',
            'green' => 'bg-green-100 text-green-700',
            'navy' => 'bg-navy/10 text-navy',
            'amber' => 'bg-amber-100 text-amber-700',
        ];
    @endphp
    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500">{{ $card['label'] }}</p>
                <div class="mt-1 flex flex-wrap items-baseline gap-x-3">
                    @foreach ($card['values'] as $value)
                        <p class="text-2xl font-extrabold text-navy-dark">{{ $value }}</p>
                    @endforeach
                </div>
                <span class="mt-2 inline-block rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $tones[$card['tone']] }}">{{ $card['sub'] }}</span>
            </div>
        @endforeach
    </div>

    {{-- Where donations are coming from --}}
    @if ($cities->isNotEmpty())
        <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-bold text-navy-dark">Where donations are coming from</h3>
            <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($cities as $city)
                    <div class="flex items-center justify-between rounded-xl bg-cream/60 px-4 py-2.5">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-navy-dark">{{ $city->city }}</p>
                            <p class="text-xs text-gray-500">{{ $city->donations }} {{ \Illuminate\Support\Str::plural('donation', $city->donations) }}</p>
                        </div>
                        <span class="shrink-0 text-sm font-bold text-brand">{{ ($symbols[$city->currency] ?? '') }}{{ number_format((float) $city->amount, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="mb-4 flex flex-wrap items-center gap-2">
        <input type="search" name="q" value="{{ $search }}" placeholder="Search reference, name, email, city..."
               class="h-10 min-w-64 flex-1 rounded-lg border border-gray-200 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/25">
        <select name="status" class="h-10 rounded-lg border border-gray-200 bg-white px-3 text-sm text-navy-dark outline-none focus:border-brand">
            <option value="">All statuses</option>
            <option value="paid" @selected($status === 'paid')>Paid</option>
            <option value="pending" @selected($status === 'pending')>Not completed</option>
        </select>
        <button type="submit" class="h-10 rounded-lg bg-navy px-5 text-sm font-semibold text-white transition hover:bg-navy-dark">Filter</button>
        @if ($search !== '' || $status)
            <a href="{{ route('admin.donations.index') }}" class="h-10 rounded-lg border border-gray-200 px-4 text-sm font-semibold leading-10 text-navy transition hover:bg-gray-50">Clear</a>
        @endif
    </form>

    @if ($donations->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M20.8 5.6a5.5 5.5 0 0 0-7.8 0L12 6.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 22l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-semibold text-navy-dark">No donations yet</h3>
            <p class="mt-1 text-sm text-gray-500">Donations taken through the website will appear here.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Donor</th>
                            <th class="px-5 py-3 font-semibold">Reference</th>
                            <th class="px-5 py-3 font-semibold">Amount</th>
                            <th class="px-5 py-3 font-semibold">Paid via</th>
                            <th class="px-5 py-3 font-semibold">Date</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($donations as $d)
                            @php
                                $symbol = ['GBP' => '£', 'USD' => '$', 'CAD' => 'CA$'][$d->currency] ?? '£';
                                $flag = config('countries.list.'.$d->region.'.flag', '');
                            @endphp
                            <tr class="nf-adm-row transition hover:bg-gray-50/70">
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-navy-dark">{{ $d->first_name }} {{ $d->last_name }}</p>
                                    <p class="truncate text-xs text-gray-400">{{ $d->email }}</p>
                                    @if ($d->city)
                                        <p class="text-xs text-gray-400">{{ $flag }} {{ $d->city }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <span class="font-mono text-xs text-gray-600">{{ $d->reference }}</span>
                                    @if ($d->gift_aid)
                                        <span class="ml-1 inline-block rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-bold text-green-700">Gift Aid</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <p class="font-bold text-navy-dark">
                                        {{ $symbol }}{{ number_format((float) $d->total, 2) }}
                                        @if ($d->frequency === 'monthly')
                                            <span class="ml-0.5 rounded-full bg-brand/10 px-1.5 py-0.5 text-[10px] font-bold text-brand">/mo</span>
                                        @endif
                                    </p>
                                    @if ((float) $d->fee > 0)
                                        <p class="text-xs text-gray-400">incl. {{ $symbol }}{{ number_format((float) $d->fee, 2) }} fee</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @if ($d->status === 'paid')
                                        <span class="inline-block rounded-full bg-green-100 px-2 py-0.5 text-[11px] font-semibold text-green-700">
                                            {{ $d->payment_provider ? ucfirst($d->payment_provider) : 'Paid' }}
                                        </span>
                                    @elseif ($d->status === 'active')
                                        <span class="inline-block rounded-full bg-green-100 px-2 py-0.5 text-[11px] font-semibold text-green-700">Active</span>
                                    @elseif ($d->status === 'cancelled')
                                        <span class="inline-block rounded-full bg-gray-200 px-2 py-0.5 text-[11px] font-semibold text-gray-600">Cancelled</span>
                                    @else
                                        <span class="inline-block rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-700">Not completed</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-gray-500">
                                    {{ ($d->paid_at ?? $d->created_at)?->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.donations.show', $d) }}"
                                           class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('admin.donations.destroy', $d) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-admin-delete data-label="donation {{ $d->reference }}"
                                                    class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($donations->hasPages())
            <div class="mt-5">{{ $donations->links() }}</div>
        @endif
    @endif

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.nf-adm-row').forEach((row, i) => setTimeout(() => row.classList.add('in'), 40 + i * 45));
</script>
@endpush
