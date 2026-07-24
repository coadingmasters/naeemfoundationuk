@extends('layouts.app')

@section('title', 'Zakat Calculator — ' . config('app.name'))

@php
    $sym = region('symbol');
    // Approximate silver nisab per region — the donor can adjust with today's price.
    $nisabDefault = ['GB' => 400, 'US' => 500, 'CA' => 680][region('code')] ?? 400;

    $assetGroups = [
        [
            'title' => 'Cash & Savings',
            'icon' => '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 10v4M18 10v4" stroke-linecap="round"/>',
            'fields' => ['Cash in hand', 'Bank accounts', 'Savings & ISAs'],
        ],
        [
            'title' => 'Gold & Silver',
            'icon' => '<path d="M5 8h14l-2 11H7L5 8zM5 8l2-4h10l2 4" stroke-linejoin="round"/>',
            'note' => 'Enter the current market value.',
            'fields' => ['Gold — market value', 'Silver — market value'],
        ],
        [
            'title' => 'Investments & Shares',
            'icon' => '<path d="M4 19V5M4 19h16M8 15l3-4 3 2 4-6" stroke-linecap="round" stroke-linejoin="round"/>',
            'fields' => ['Shares, funds & stocks', 'Pension (accessible)', 'Crypto assets'],
        ],
        [
            'title' => 'Business Assets',
            'icon' => '<path d="M3 9l1-4h16l1 4M4 9v10a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V9M4 9h16" stroke-linecap="round" stroke-linejoin="round"/>',
            'fields' => ['Stock & raw materials', 'Money owed to you'],
        ],
    ];
    $liabilityFields = ['Debts due now', 'Bills & rent due', 'Money you owe'];
@endphp

@section('content')

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-navy via-navy to-navy-dark pb-20 pt-48 text-white sm:pb-24 sm:pt-52">
        {{-- Animated glows + floating specks --}}
        <div class="nf-float pointer-events-none absolute -right-20 top-6 h-72 w-72 rounded-full bg-brand/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-24 bottom-0 h-80 w-80 rounded-full bg-white/5 blur-3xl"></div>
        <span class="nf-float pointer-events-none absolute left-[13%] top-[38%] h-3 w-3 rounded-full bg-brand/60"></span>
        <span class="nf-float pointer-events-none absolute right-[15%] top-[32%] h-2 w-2 rounded-full bg-white/40" style="animation-delay:-1.5s"></span>
        <span class="nf-float pointer-events-none absolute right-[22%] bottom-[26%] h-2.5 w-2.5 rounded-full bg-brand/50" style="animation-delay:-2.6s"></span>
        <span class="nf-float pointer-events-none absolute left-[20%] bottom-[30%] h-2 w-2 rounded-full bg-white/30" style="animation-delay:-3.4s"></span>

        <div class="nf-container relative text-center">
            <span class="nf-reveal inline-flex items-center gap-2.5 rounded-full bg-white/10 py-1.5 pl-1.5 pr-4 text-xs font-semibold uppercase tracking-wide ring-1 ring-white/15">
                <span class="nf-zk-badge relative grid h-7 w-7 place-items-center rounded-full bg-brand text-[11px] font-extrabold">2.5%</span>
                Purify your wealth
            </span>
            <h1 class="nf-reveal mt-6 text-4xl font-extrabold tracking-tight sm:text-5xl md:text-6xl" data-reveal-delay="90">Zakat Calculator</h1>
            <p class="nf-reveal mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base" data-reveal-delay="160">
                Zakat is 2.5% of the wealth you’ve held for a lunar year, once it passes the nisab threshold.
                Enter your assets below and we’ll work out exactly what’s due.
            </p>
        </div>
    </section>

    {{-- ===================== CALCULATOR ===================== --}}
    <section class="bg-cream/40 py-12 sm:py-16" data-zakat-calc>
        <div class="nf-container grid gap-8 lg:grid-cols-3 lg:items-start">

            {{-- Inputs --}}
            <div class="space-y-6 lg:col-span-2">
                @foreach ($assetGroups as $g)
                    <div class="nf-reveal rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-7" data-reveal-delay="{{ $loop->index * 60 }}">
                        <div class="flex items-center gap-3">
                            <span class="grid h-10 w-10 place-items-center rounded-xl bg-brand/10 text-brand">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $g['icon'] !!}</svg>
                            </span>
                            <div>
                                <h2 class="text-base font-bold text-navy-dark">{{ $g['title'] }}</h2>
                                @isset($g['note'])<p class="text-xs text-gray-400">{{ $g['note'] }}</p>@endisset
                            </div>
                        </div>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            @foreach ($g['fields'] as $f)
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-navy-dark">{{ $f }}</label>
                                    <div class="relative">
                                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-400">{{ $sym }}</span>
                                        <input type="number" min="0" step="0.01" inputmode="decimal" data-zakat placeholder="0.00"
                                               class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-9 pr-3 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- Liabilities --}}
                <div class="nf-reveal rounded-2xl border border-red-100 bg-white p-6 shadow-sm sm:p-7">
                    <div class="flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-red-50 text-red-600">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14" stroke-linecap="round"/></svg>
                        </span>
                        <div>
                            <h2 class="text-base font-bold text-navy-dark">Liabilities to deduct</h2>
                            <p class="text-xs text-gray-400">Immediate debts you owe are subtracted from your wealth.</p>
                        </div>
                    </div>
                    <div class="mt-5 grid gap-4 sm:grid-cols-3">
                        @foreach ($liabilityFields as $f)
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-navy-dark">{{ $f }}</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-400">{{ $sym }}</span>
                                    <input type="number" min="0" step="0.01" inputmode="decimal" data-zakat-liability placeholder="0.00"
                                           class="h-11 w-full rounded-lg border border-gray-300 bg-white pl-9 pr-3 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Live summary --}}
            <div class="lg:sticky lg:top-24">
                <div class="nf-reveal overflow-hidden rounded-2xl bg-navy text-white shadow-xl" data-reveal-delay="120">
                    <div class="border-b border-white/10 p-6">
                        <p class="text-xs font-semibold uppercase tracking-wide text-white/50">Your Zakat</p>
                        <p class="mt-2 text-4xl font-extrabold" data-zakat-due>{{ $sym }}0.00</p>
                        <span class="nf-zk-status mt-3" data-zakat-status>Enter your assets to begin</span>
                    </div>

                    <div class="space-y-3 p-6 text-sm">
                        <div class="flex items-center justify-between"><span class="text-white/70">Total assets</span><span class="font-semibold" data-zakat-total>{{ $sym }}0.00</span></div>
                        <div class="flex items-center justify-between"><span class="text-white/70">Liabilities</span><span class="font-semibold text-red-300" data-zakat-liab>−{{ $sym }}0.00</span></div>
                        <div class="flex items-center justify-between border-t border-white/10 pt-3"><span class="text-white/70">Net zakatable wealth</span><span class="font-extrabold" data-zakat-net>{{ $sym }}0.00</span></div>

                        <div class="mt-2 rounded-xl bg-white/5 p-3">
                            <label class="mb-1 block text-xs font-medium text-white/60">Nisab threshold (silver)</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-white/50">{{ $sym }}</span>
                                <input type="number" min="0" step="0.01" data-zakat-nisab value="{{ $nisabDefault }}"
                                       class="h-10 w-full rounded-lg border border-white/15 bg-white/5 pl-9 pr-3 text-sm text-white outline-none transition focus:border-white/40">
                            </div>
                            <p class="mt-1 text-[11px] text-white/40">Approx. value of 612.36g of silver — update with today’s price.</p>
                        </div>
                    </div>

                    <div class="border-t border-white/10 p-6">
                        <form method="POST" action="{{ route('donate.add') }}" data-zakat-give>
                            @csrf
                            <input type="hidden" name="cause" value="Zakat">
                            <input type="hidden" name="amount" data-zakat-amount-input value="0">
                            <input type="hidden" name="image" value="images/zakathero.png">
                            <button type="submit" data-zakat-give-btn disabled
                                    class="btn-brand w-full justify-center py-3 disabled:cursor-not-allowed disabled:opacity-50">
                                Give <span data-zakat-give-label>{{ $sym }}0.00</span> Zakat
                            </button>
                        </form>
                        <p class="mt-3 text-center text-[11px] text-white/40">Your Zakat is added to your basket — pay securely at checkout.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== INFO ===================== --}}
    <section class="py-14">
        <div class="nf-container grid gap-8 lg:grid-cols-3">
            @foreach ([
                ['2.5%', 'The Zakat rate', 'Zakat is 2.5% of the qualifying wealth you’ve held for one lunar year (hawl).'],
                ['Nisab', 'The threshold', 'Zakat is only due once your net wealth exceeds the nisab — the value of 87.48g gold or 612.36g silver. We use silver, as it benefits more recipients.'],
                ['Zakatable', 'What counts', 'Cash, gold, silver, shares, business stock and money owed to you — minus your immediate debts.'],
            ] as $i => $c)
                <div class="nf-reveal rounded-2xl border border-gray-100 bg-white p-6 shadow-sm" data-reveal-delay="{{ $i * 70 }}">
                    <span class="text-2xl font-extrabold text-brand">{{ $c[0] }}</span>
                    <h3 class="mt-2 text-base font-bold text-navy-dark">{{ $c[1] }}</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $c[2] }}</p>
                </div>
            @endforeach
        </div>
        <p class="nf-container mt-6 text-center text-xs text-gray-400">This calculator is a guide. For complex estates, please consult a scholar.</p>
    </section>

@endsection
