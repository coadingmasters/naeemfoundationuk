@extends('layouts.app')

@section('title', 'Schedule Your Friday Giving — ' . config('app.name'))

{{-- Light hero → keep the header solid. --}}
@section('header-solid', 'yes')

@php
    $amounts = config('friday-giving.amounts');
    $popular = config('friday-giving.popular');
    $defaultAmount = (float) config('friday-giving.default_amount');
    $causes = config('friday-giving.causes');
    $defaultCause = $causes[0];

    // Banner photo — admin-managed (Admin -> Hero Banners), falls back to the
    // built-in default when no override is set.
    $heroImage = \App\Support\PageHeroes::resolve('schedule-friday-giving', 'images/givngdropdown.jpeg');
    $mobileHeroImage = \App\Support\PageHeroes::resolveMobile('schedule-friday-giving');

    // Cause icons (single-path SVGs) — same set as the Ramadan scheduler.
    $causeIcons = [
        'Zakat' => 'M12 3v18M8 7h6a3 3 0 0 1 0 6H9a3 3 0 0 0 0 6h7',
        'Sadaqah' => 'M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z',
        'Orphans' => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21a8 8 0 0 1 16 0',
        'Education' => 'M22 9L12 4 2 9l10 5 10-5zM6 12v5c0 1.5 3 3 6 3s6-1.5 6-3v-5',
    ];
@endphp

@section('content')

    {{-- ===================== BANNER ===================== --}}
    <section class="bg-cream">
        <div class="nf-container">
            <div class="grid items-center gap-10 py-12 lg:grid-cols-2 lg:gap-16 lg:py-20">
                {{-- Text --}}
                <div>
                    <h1 class="text-4xl font-extrabold leading-[1.1] text-brand sm:text-5xl lg:text-6xl">
                        Automate your giving<br>every Friday
                    </h1>
                    <p class="mt-6 max-w-md text-base leading-relaxed text-gray-600">
                        Jumu&rsquo;ah is the most blessed day of the week for charity. Set your amount once and
                        we&rsquo;ll take care of the rest — or give just once, whenever you&rsquo;re ready.
                    </p>
                </div>

                {{-- Image --}}
                <div>
                    @if ($mobileHeroImage)
                        <img src="{{ asset($mobileHeroImage) }}" alt="" class="aspect-video w-full rounded-xl object-cover shadow-md lg:hidden">
                        <img src="{{ asset($heroImage) }}" alt="" class="hidden aspect-video w-full rounded-xl object-cover shadow-md lg:block">
                    @else
                        <img src="{{ asset($heroImage) }}" alt="" class="aspect-video w-full rounded-xl object-cover shadow-md">
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== SCHEDULER ===================== --}}
    <section class="bg-cream pb-12 sm:pb-16">
        <div class="nf-container">

            <form method="POST" action="{{ route('donate.add') }}" data-friday
                  class="grid items-start gap-8 lg:grid-cols-2 lg:gap-10">
                @csrf
                <input type="hidden" name="frequency" data-fg-frequency-input value="one-off">
                <input type="hidden" name="image" value="images/changinslives2.jpg">
                <input type="hidden" name="cause" data-fg-cause-input value="{{ $defaultCause }} (Friday Giving)">
                <input type="hidden" name="amount" data-fg-amount-input value="{{ $defaultAmount }}">

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

                    {{-- One-off vs every Friday --}}
                    <div>
                        <h2 class="text-sm font-bold text-navy-dark">How would you like to give?</h2>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <button type="button" data-fg-freq="one-off" class="nf-rg-option w-full is-selected">One-Off</button>
                            <button type="button" data-fg-freq="weekly" class="nf-rg-option w-full">Every Friday</button>
                        </div>
                        <p class="mt-2 text-xs leading-relaxed text-gray-500" data-fg-freq-hint>
                            Charged once as a single payment.
                        </p>
                    </div>

                    {{-- Amount --}}
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/5 sm:p-6">
                        <h2 class="text-base font-bold text-navy-dark">Enter your amount</h2>

                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                            @foreach ($amounts as $amount)
                                <div class="relative">
                                    @if ($amount === $popular)
                                        <span class="absolute -top-2 left-1/2 z-10 -translate-x-1/2 rounded-full bg-brand px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide text-white">Popular</span>
                                    @endif
                                    <button type="button" data-fg-amount="{{ $amount }}"
                                            class="nf-rg-option w-full {{ (float) $amount === $defaultAmount ? 'is-selected' : '' }}">
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
                            <input type="number" min="1" step="0.01" value="{{ $defaultAmount }}" data-fg-custom
                                   aria-label="Amount in pounds"
                                   class="w-full border-0 bg-transparent p-0 text-xl font-bold text-navy-dark focus:outline-none focus:ring-0">
                        </div>
                    </div>
                </div>

                {{-- ================= RIGHT ================= --}}
                <div class="rounded-2xl bg-white p-5 shadow-lg ring-1 ring-black/5 sm:p-6 lg:sticky lg:top-28">
                    <h2 class="text-base font-bold text-navy-dark">Select your cause</h2>

                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach ($causes as $cause)
                            <button type="button" data-fg-cause="{{ $cause }}"
                                    class="nf-rg-cause {{ $cause === $defaultCause ? 'is-selected' : '' }}">
                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                    <path d="{{ $causeIcons[$cause] }}" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ $cause }}
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-6 flex items-center justify-between border-t border-gray-200 pt-4">
                        <p class="text-base font-bold text-navy-dark" data-fg-total-label>Total</p>
                        <p class="text-xl font-extrabold text-brand" data-fg-total>{{ region('symbol') }}0.00</p>
                    </div>
                    <p class="mt-2 hidden text-xs leading-relaxed text-gray-500" data-fg-recur-note>
                        PayPal will automatically take this every Friday until you cancel — you can cancel any time.
                    </p>

                    {{-- Continue --}}
                    <button type="submit" data-fg-submit
                            class="mt-5 flex w-full items-center justify-between gap-2 rounded-xl bg-navy px-5 py-3.5 text-sm font-bold text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-navy-dark hover:shadow-lg disabled:cursor-not-allowed disabled:opacity-60">
                        <span class="flex items-center gap-2">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                            Continue with card
                        </span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>

                    <p class="mt-4 text-center text-xs leading-relaxed text-gray-500">
                        You&rsquo;ll confirm your Gift Aid declaration, donor details and payment on the next steps.
                        By continuing you agree to our
                        <a href="{{ route('privacy-policy') }}" class="font-semibold text-navy underline hover:text-brand">Privacy Policy</a>.
                    </p>
                </div>
            </form>
        </div>
    </section>

@endsection
