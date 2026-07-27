@extends('admin.layouts.app')

@section('title', 'Donation')
@section('heading', 'Donation ' . $donation->reference)
@section('subheading', 'Full donor, cause and payment details.')

@section('actions')
    <a href="{{ route('admin.donations.index') }}"
       class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-navy transition hover:border-brand hover:text-brand">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M11 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        All donations
    </a>
@endsection

@section('content')
    @php
        $symbol = ['GBP' => '£', 'USD' => '$', 'CAD' => 'CA$'][$donation->currency] ?? '£';
        $m = fn ($v) => $symbol.number_format((float) $v, 2);
        $paid = $donation->status === 'paid';
    @endphp

    <div class="grid gap-6 lg:grid-cols-3 lg:items-start">

        <div class="space-y-6 lg:col-span-2">

            {{-- What they gave to --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-7">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-bold text-navy-dark">What they donated to</h3>
                    @if ($paid)
                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-semibold text-green-700">Paid {{ $donation->paid_at?->format('d M Y') }}</span>
                    @else
                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-700">Not completed</span>
                    @endif
                </div>

                <div class="mt-4 divide-y divide-gray-100 border-y border-gray-100">
                    @forelse ($donation->items ?? [] as $item)
                        <div class="flex items-center justify-between py-3">
                            <div class="min-w-0">
                                <p class="font-semibold text-navy-dark">{{ $item['cause'] ?? 'Donation' }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ $item['qty'] ?? 1 }} &times; {{ $m($item['amount'] ?? 0) }}
                                    @if (($item['frequency'] ?? 'one-off') !== 'one-off')
                                        &middot; {{ ucfirst($item['frequency']) }}
                                    @endif
                                </p>
                            </div>
                            <span class="shrink-0 font-bold text-navy-dark">{{ $m(($item['amount'] ?? 0) * ($item['qty'] ?? 1)) }}</span>
                        </div>
                    @empty
                        <p class="py-3 text-sm text-gray-500">No line items recorded.</p>
                    @endforelse
                </div>

                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Subtotal</dt>
                        <dd class="font-semibold text-navy-dark">{{ $m($donation->subtotal) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Transaction fee covered</dt>
                        <dd class="font-semibold text-navy-dark">{{ $donation->cover_fee ? $m($donation->fee) : '—' }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-gray-100 pt-2 text-base">
                        <dt class="font-bold text-navy-dark">Total</dt>
                        <dd class="font-extrabold text-brand">{{ $m($donation->total) }}</dd>
                    </div>
                </dl>

                @if ($donation->gift_aid)
                    <div class="mt-4 rounded-xl border border-green-200 bg-green-50 p-4">
                        <p class="text-sm font-bold text-green-800">Gift Aid declared</p>
                        <p class="mt-0.5 text-xs text-green-700">
                            You can reclaim an extra {{ $m($donation->subtotal * 0.25) }} from HMRC on this donation.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Payment --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-7">
                <h3 class="text-base font-bold text-navy-dark">Payment</h3>
                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Method</dt>
                        <dd class="font-semibold text-navy-dark">{{ $donation->payment_provider ? ucfirst($donation->payment_provider) : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Status</dt>
                        <dd class="font-semibold {{ $paid ? 'text-green-700' : 'text-amber-700' }}">{{ ucfirst($donation->status) }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium text-gray-400">Transaction ID</dt>
                        <dd class="break-all font-mono text-xs text-navy-dark">{{ $donation->payment_id ?: '—' }}</dd>
                        @if ($donation->payment_id)
                            <p class="mt-1 text-xs text-gray-400">Search this ID in your PayPal dashboard to find the transaction.</p>
                        @endif
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Paid at</dt>
                        <dd class="font-semibold text-navy-dark">{{ $donation->paid_at?->format('d M Y · g:i a') ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Started at</dt>
                        <dd class="font-semibold text-navy-dark">{{ $donation->created_at?->format('d M Y · g:i a') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Donor --}}
        <div class="lg:sticky lg:top-24 space-y-6">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h3 class="text-base font-bold text-navy-dark">Donor</h3>

                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Name</dt>
                        <dd class="font-semibold text-navy-dark">{{ $donation->first_name }} {{ $donation->last_name }}</dd>
                    </div>
                    @if ($donation->organisation_name)
                        <div>
                            <dt class="text-xs font-medium text-gray-400">On behalf of</dt>
                            <dd class="font-semibold text-navy-dark">{{ $donation->organisation_name }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Email</dt>
                        <dd><a href="mailto:{{ $donation->email }}" class="font-semibold text-brand hover:underline">{{ $donation->email }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Phone</dt>
                        <dd><a href="tel:{{ $donation->phone }}" class="font-semibold text-navy-dark hover:text-brand">{{ $donation->phone }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Address</dt>
                        <dd class="font-semibold text-navy-dark">
                            {{ $donation->billing_address }}<br>
                            {{ $donation->city }} {{ $donation->postcode }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Region</dt>
                        <dd class="font-semibold text-navy-dark">
                            {{ config('countries.list.'.$donation->region.'.flag', '') }}
                            {{ config('countries.list.'.$donation->region.'.name', $donation->region) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-400">Keep in touch</dt>
                        <dd class="mt-1 flex flex-wrap gap-1.5">
                            @php
                                $consent = $donation->contact_consent ?? [];
                                $channels = collect(['email' => 'Email', 'phone' => 'Phone', 'sms' => 'SMS'])
                                    ->filter(fn ($label, $key) => ! empty($consent[$key]));
                            @endphp
                            @if ($channels->isNotEmpty())
                                @foreach ($channels as $label)
                                    <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-[11px] font-semibold text-green-700">{{ $label }}</span>
                                @endforeach
                            @elseif ($donation->contact_consent !== null)
                                <span class="text-sm font-semibold text-navy-dark">Opted out of all contact</span>
                            @else
                                <span class="text-sm text-gray-400">Not asked</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                <form method="POST" action="{{ route('admin.donations.destroy', $donation) }}" class="mt-5 border-t border-gray-100 pt-4">
                    @csrf
                    @method('DELETE')
                    <button type="button" data-admin-delete data-label="donation {{ $donation->reference }}"
                            class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Delete record
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
