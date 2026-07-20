@extends('layouts.app')

@section('title', $appeal->title . ' — ' . config('app.name'))

{{-- Solid header: this page opens with an image banner directly under the bar. --}}
@section('header-solid', 'yes')

@section('content')

    {{-- ===================== HERO BANNER ===================== --}}
    <section class="relative overflow-hidden">
        <div class="relative min-h-[300px] sm:min-h-[360px] lg:min-h-[440px]">
            <img src="{{ asset($appeal->image) }}" alt="{{ $appeal->title }}"
                 class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-navy-dark/90 via-navy-dark/65 to-navy-dark/25"></div>

            <div class="relative flex min-h-[300px] items-center sm:min-h-[360px] lg:min-h-[440px]">
                <div class="nf-container py-12">
                    <nav class="mb-4 flex items-center gap-2 text-sm text-white/70">
                        <a href="{{ route('home') }}" class="transition-colors hover:text-white">Home</a>
                        <span aria-hidden="true">&rsaquo;</span>
                        <span class="text-white">Appeals</span>
                    </nav>

                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-white ring-1 ring-white/20">
                        <span class="h-1.5 w-1.5 rounded-full bg-brand"></span> Appeal
                    </span>

                    <h1 class="mt-4 max-w-2xl text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-5xl">
                        {{ $appeal->title }}
                    </h1>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== STORY + DONATE ===================== --}}
    <section class="bg-cream py-12 sm:py-16">
        <div class="nf-container grid gap-8 lg:grid-cols-5 lg:items-start lg:gap-10">

            {{-- Left: the appeal story --}}
            <div class="lg:col-span-3">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5 sm:p-8 lg:p-10">
                    <p class="text-sm font-bold uppercase tracking-wider text-brand">About this appeal</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-navy-dark sm:text-3xl">{{ $appeal->title }}</h2>

                    <div class="mt-5 space-y-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                        {!! nl2br(e($appeal->description)) !!}
                    </div>

                    <p class="mt-5 text-sm leading-relaxed text-gray-600 sm:text-base">
                        Your generosity helps Naeem Foundation deliver this support directly to the families who need it
                        most. Every contribution — large or small — makes a lasting difference.
                    </p>

                    <h3 class="mt-8 text-lg font-bold text-navy-dark">How your donation helps</h3>
                    <ul class="mt-3 space-y-2.5 text-sm leading-relaxed text-gray-600 sm:text-base">
                        @foreach ([
                            'Delivered directly to those in need, with full transparency.',
                            'Supports both urgent relief and long-term, sustainable change.',
                            'Every donation is tracked and reported in our annual accounts.',
                        ] as $point)
                            <li class="flex items-start gap-2.5">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <p class="mt-6 text-sm leading-relaxed text-gray-600 sm:text-base">
                        Whether you give once or set up a monthly gift, you become part of a community changing lives —
                        thank you for standing with the families we serve.
                    </p>

                    {{-- Reassurance --}}
                    <div class="mt-8 grid gap-5 border-t border-gray-100 pt-7 sm:grid-cols-3">
                        <div class="flex items-start gap-3">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand/10 text-brand">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s-7.5-4.6-9.5-9A5.2 5.2 0 0 1 12 6.6a5.2 5.2 0 0 1 9.5 5.4c-2 4.4-9.5 9-9.5 9Z" stroke-linejoin="round"/></svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-navy-dark">100% Donation Policy</p>
                                <p class="mt-0.5 text-xs text-gray-500">Every penny reaches those in need.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand/10 text-brand">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v18M3 8h18M6 12h12M8 16h8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-navy-dark">Gift Aid +25%</p>
                                <p class="mt-0.5 text-xs text-gray-500">Boost your gift at no extra cost.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand/10 text-brand">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-navy-dark">Safe &amp; Secure</p>
                                <p class="mt-0.5 text-xs text-gray-500">Encrypted card &amp; PayPal payments.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('home') }}#appeals"
                   class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-navy transition-colors hover:text-brand">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M11 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Back to all appeals
                </a>
            </div>

            {{-- Right: donate widget for this appeal (sticky on desktop) --}}
            <div class="lg:col-span-2 lg:sticky lg:top-28">
                @include('partials.donate-widget', [
                    'widgetCauses' => [$appeal->title],
                    'widgetImage'  => $appeal->image,
                    'widgetTitle'  => 'Donate to this appeal',
                ])
            </div>
        </div>
    </section>

@endsection
