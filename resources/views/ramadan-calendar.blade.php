@extends('layouts.app')

@section('title', 'Ramadan Calendar — ' . config('app.name'))

{{-- Light hero → keep the header solid. --}}
@section('header-solid', 'yes')

@php
    $nights = (int) config('ramadan.nights');
    $amounts = config('ramadan.amounts');
    $popular = config('ramadan.popular');
    $defaultDaily = (float) config('ramadan.default_daily');
    $boosts = config('ramadan.boosts');
    $causes = config('ramadan.causes');
    $defaultCause = $causes[0];

    $starts = \Illuminate\Support\Carbon::parse(config('ramadan.starts_at'));
    $ends = \Illuminate\Support\Carbon::parse(config('ramadan.ends_at'));

    // Cause icons (single-path SVGs)
    $causeIcons = [
        'Zakat' => 'M12 3v18M8 7h6a3 3 0 0 1 0 6H9a3 3 0 0 0 0 6h7',
        'Sadaqah' => 'M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z',
        'Orphans' => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21a8 8 0 0 1 16 0',
        'Education' => 'M22 9L12 4 2 9l10 5 10-5zM6 12v5c0 1.5 3 3 6 3s6-1.5 6-3v-5',
    ];
@endphp

@section('content')

    <section class="bg-cream py-12 sm:py-16">
        <div class="nf-container">

            <h1 class="text-3xl font-extrabold leading-tight text-navy sm:text-4xl lg:text-5xl">
                Automate your giving this Ramadan
            </h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 sm:text-base">
                Give a little every night of Ramadan. Set your daily amount once and we’ll take care of the rest —
                including a boost on the blessed 27th night.
            </p>

            <form method="POST" action="{{ route('donate.add') }}" data-ramadan data-nights="{{ $nights }}"
                  class="mt-10 grid items-start gap-8 lg:grid-cols-2 lg:gap-10">
                @csrf
                <input type="hidden" name="frequency" value="one-off">
                <input type="hidden" name="image" value="images/changinslives2.jpg">
                <input type="hidden" name="cause" data-rg-cause-input value="{{ $defaultCause }} (Ramadan {{ $nights }} Nights)">
                <input type="hidden" name="amount" data-rg-amount-input value="{{ $defaultDaily * $nights }}">

                {{-- ================= LEFT ================= --}}
                <div class="space-y-6">

                    {{-- Organisation --}}
                    <div class="flex items-center justify-between gap-4 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 w-12 rounded-full object-contain">
                            <div>
                                <p class="text-xs text-gray-500">Automate giving with</p>
                                <p class="text-sm font-extrabold uppercase tracking-wide text-navy-dark">{{ config('app.name') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Nights tab --}}
                    <div class="border-b border-gray-200">
                        <span class="inline-block border-b-2 border-brand pb-2 text-base font-bold text-brand">
                            {{ $nights }} Nights
                        </span>
                    </div>

                    {{-- Daily amount --}}
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/5 sm:p-6">
                        <h2 class="text-base font-bold text-navy-dark">Enter your daily amount</h2>

                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                            @foreach ($amounts as $amount)
                                <div class="relative">
                                    @if ($amount === $popular)
                                        <span class="absolute -top-2 left-1/2 z-10 -translate-x-1/2 rounded-full bg-brand px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide text-white">Popular</span>
                                    @endif
                                    <button type="button" data-rg-amount="{{ $amount }}"
                                            class="nf-rg-option w-full {{ (float) $amount === $defaultDaily ? 'is-selected' : '' }}">
                                        {{ region('symbol') }}{{ $amount }}
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        {{-- Custom amount --}}
                        <div class="mt-4 flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3">
                            <span class="flex items-center gap-1.5 border-r border-gray-200 pr-3 text-sm font-semibold text-navy-dark">
                                🇬🇧 GBP
                            </span>
                            <span class="text-xl font-bold text-navy-dark">{{ region('symbol') }}</span>
                            <input type="number" min="1" step="0.01" value="{{ $defaultDaily }}" data-rg-custom
                                   aria-label="Daily amount in pounds"
                                   class="w-full border-0 bg-transparent p-0 text-xl font-bold text-navy-dark focus:outline-none focus:ring-0">
                        </div>

                        {{-- 27th night boost --}}
                        <div class="mt-5 rounded-xl border border-brand/20 bg-brand/5 p-4 text-center">
                            <p class="text-sm font-bold text-navy-dark">Increase my donation on the 27th night</p>
                            <p class="mt-1 text-xs text-gray-600">
                                Add <span class="font-bold text-brand" data-rg-boost-amount>{{ region('symbol') }}0.00</span> for the 27th night
                            </p>

                            <div class="mt-3 flex flex-wrap justify-center gap-1 rounded-full bg-white p-1">
                                @foreach ($boosts as $boost)
                                    <button type="button" data-rg-boost="{{ $boost }}"
                                            class="nf-rg-pill {{ $boost === 0 ? 'is-selected' : '' }}">
                                        {{ $boost }}%
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= RIGHT ================= --}}
                <div class="rounded-2xl bg-white p-5 shadow-lg ring-1 ring-black/5 sm:p-6 lg:sticky lg:top-28">
                    <h2 class="text-base font-bold text-navy-dark">Select your cause</h2>

                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach ($causes as $cause)
                            <button type="button" data-rg-cause="{{ $cause }}"
                                    class="nf-rg-cause {{ $cause === $defaultCause ? 'is-selected' : '' }}">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="{{ $causeIcons[$cause] }}" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ $cause }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="mt-6 border-t border-gray-200 pt-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-bold text-navy-dark">Total nights</p>
                                <p class="text-xs text-gray-500">{{ $starts->format('j M') }} to {{ $ends->format('j M') }}</p>
                            </div>
                            <p class="text-sm font-bold text-navy-dark">{{ $nights }} nights</p>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-4">
                        <p class="text-base font-bold text-navy-dark">Total</p>
                        <p class="text-xl font-extrabold text-brand" data-rg-total>{{ region('symbol') }}0.00</p>
                    </div>

                    {{-- Continue --}}
                    <button type="submit" data-rg-submit
                            class="mt-5 flex w-full items-center justify-between gap-2 rounded-xl bg-navy px-5 py-3.5 text-sm font-bold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-navy-dark hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-60">
                        <span class="flex items-center gap-2">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                            Continue with card
                        </span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>

                    <p class="mt-4 text-center text-xs leading-relaxed text-gray-500">
                        You’ll confirm your Gift Aid declaration, donor details and payment on the next steps.
                        By continuing you agree to our
                        <a href="{{ route('privacy-policy') }}" class="font-semibold text-navy underline hover:text-brand">Privacy Policy</a>.
                    </p>
                </div>
            </form>
        </div>
    </section>

@endsection
