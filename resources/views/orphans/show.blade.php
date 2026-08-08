@extends('layouts.app')

@section('title', $orphan->name . ' — Sponsor an Orphan — ' . config('app.name'))

@php
    // Suggested recurring amounts (fall back to a sensible default).
    $monthly = $orphan->monthly_amount ?: 53;

    $facts = array_filter([
        'DOB' => $orphan->dob,
        'Location' => $orphan->location,
        'Grade' => $orphan->grade,
    ]);
@endphp

@section('content')

    {{-- ===================== HERO: photo + facts (left) · donate widget (right) ===================== --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-navy via-navy to-navy-dark">
        <div class="pointer-events-none absolute -right-24 top-0 h-72 w-72 rounded-full bg-brand/25 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-24 -bottom-10 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>

        <div class="nf-container relative pb-14 pt-32 sm:pt-36 lg:pb-20 lg:pt-40">
            {{-- Back link --}}
            <a href="{{ route('orphans-sponsorships') }}"
               class="nf-reveal inline-flex items-center gap-2 text-sm font-semibold text-white/70 transition hover:text-white">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                All orphans
            </a>

            <div class="mt-6 grid items-start gap-10 lg:grid-cols-[1fr_27rem] lg:gap-14">
                {{-- Left: portrait + facts --}}
                <div class="nf-reveal">
                    <div class="grid gap-8 sm:grid-cols-[minmax(0,20rem)_1fr] sm:items-start">
                        {{-- Portrait --}}
                        <div class="overflow-hidden rounded-2xl bg-white/5 shadow-2xl ring-1 ring-white/10">
                            <div class="aspect-[4/5] w-full overflow-hidden bg-gradient-to-b from-white/10 to-white/5">
                                @if (filled($orphan->photo))
                                    <img src="{{ asset($orphan->photo) }}" alt="{{ $orphan->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="grid h-full place-items-center">
                                        <svg class="h-24 w-24 text-white/20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4 0-8 2-8 5v1h16v-1c0-3-4-5-8-5z"/></svg>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Name + facts --}}
                        <div class="text-white">
                            <h1 class="text-3xl font-extrabold leading-tight sm:text-4xl">{{ $orphan->name }}</h1>

                            <dl class="mt-5 space-y-2.5">
                                @foreach ($facts as $label => $value)
                                    <div class="flex items-center gap-2 text-sm">
                                        <dt class="font-bold text-white/55">{{ $label }}:</dt>
                                        <dd class="font-semibold text-white">{{ $value }}</dd>
                                    </div>
                                @endforeach
                            </dl>

                            @if ($orphan->story)
                                <p class="mt-5 max-w-md text-sm leading-relaxed text-white/80">{{ $orphan->story }}</p>
                            @endif

                            <p class="mt-6 inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2.5 text-xs font-semibold text-white ring-1 ring-white/15">
                                <svg class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                100% of your donation reaches this child.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Right: reference-style donation panel, scoped to this orphan --}}
                <div class="nf-reveal" data-reveal-delay="120">
                    @include('partials.donate-panel', [
                        'panelTitle' => $orphan->name,
                        'causes' => ['Sadaqah', 'Zakat', 'Lillah'],
                        'oneOffAmounts' => [20, 50, 100],
                        'monthlyDefault' => $monthly,
                        'yearlyDefault' => $monthly * 12,
                        // Sponsorship is an ongoing commitment, so open on Monthly.
                        'defaultFreq' => 'monthly',
                        'orphanId' => $orphan->id,
                        'image' => filled($orphan->photo) ? $orphan->photo : 'images/changinslives1.jpg',
                    ])
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== IMPACT STRIP ===================== --}}
    <section class="bg-cream/40 py-14 sm:py-16">
        <div class="nf-container">
            <div class="nf-reveal mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Your sponsorship in action</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">What your support provides</h2>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-3">
                @php
                    // What the sponsorship covers. A card renders 'items' as a ticked
                    // checklist, or falls back to 'd' for a plain description.
                    $impacts = [
                        [
                            't' => 'Education',
                            'items' => ['Education', 'Books', 'Computer Lab'],
                            'i' => '<path d="M22 10L12 5 2 10l10 5 10-5zM6 12v5c0 1 3 2 6 2s6-1 6-2v-5" stroke-linecap="round" stroke-linejoin="round"/>',
                        ],
                        [
                            't' => 'Daily Care',
                            'items' => ['3 Nutritious Meals a Day', 'Safe Hostel to live & grow'],
                            'i' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>',
                        ],
                        [
                            't' => 'Healthcare',
                            'd' => 'Regular check-ups and medical treatment whenever it is needed.',
                            'i' => '<path d="M12 5v14M5 12h14" stroke-linecap="round"/><rect x="3" y="3" width="18" height="18" rx="4"/>',
                        ],
                    ];
                @endphp
                @foreach ($impacts as $i => $card)
                    <div class="nf-reveal group flex h-full flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:border-brand/25 hover:shadow-xl"
                         data-reveal-delay="{{ $i * 120 }}">
                        <span class="grid h-14 w-14 shrink-0 place-items-center rounded-full bg-brand/10 text-brand transition-colors duration-300 group-hover:bg-brand group-hover:text-white">
                            <svg class="h-7 w-7 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $card['i'] !!}</svg>
                        </span>

                        <h3 class="mt-4 text-lg font-bold text-navy-dark transition-colors duration-300 group-hover:text-brand">{{ $card['t'] }}</h3>

                        @if (! empty($card['items']))
                            {{-- Each line reveals just after its card, so the list ticks in. --}}
                            <ul class="mt-3 space-y-2.5">
                                @foreach ($card['items'] as $j => $item)
                                    <li class="nf-reveal flex items-start gap-2.5" data-reveal-delay="{{ $i * 120 + $j * 90 + 160 }}">
                                        <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-brand/10 text-brand">
                                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </span>
                                        <span class="text-sm font-medium leading-relaxed text-gray-600">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mt-3 text-sm leading-relaxed text-gray-500">{{ $card['d'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('orphans-sponsorships') }}" class="btn-brand px-7 py-3">
                    See more children
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </section>

@endsection
