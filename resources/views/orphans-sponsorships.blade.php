@extends('layouts.app')

@section('title', 'Sponsor an Orphan — ' . config('app.name'))

{{-- No image hero here, so the header must be solid. --}}
@section('header-solid', 'yes')

@php
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Hostel & Care', 'description' => 'A safe home, warm meals and study support for orphans and students.', 'link' => '#'],
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/handpump.jpg', 'title' => 'Clean Water', 'description' => 'Hand pumps and wells bringing safe water to communities in need.', 'link' => '#'],
        ]);
    }
@endphp

@section('content')

    {{-- ===================== HERO: content + video ===================== --}}
    {{-- TEMP banner background — swap 'images/changinslives1.jpg' for the real
         orphan banner when it's ready. --}}
    <section class="relative overflow-hidden pb-12 pt-32 sm:pt-36 lg:pb-16 lg:pt-40">
        <img src="{{ asset('images/changinslives1.jpg') }}" alt="" class="absolute inset-0 h-full w-full object-cover object-center">
        {{-- Light overlay keeps the navy text readable over the banner. --}}
        <div class="absolute inset-0 bg-gradient-to-r from-cream via-cream/90 to-cream/70"></div>
        <div class="nf-container relative grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            {{-- Left: content --}}
            <div class="nf-reveal">
                <span class="inline-flex items-center gap-2 rounded-full bg-brand/10 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-brand">
                    <span class="h-1.5 w-1.5 rounded-full bg-brand"></span> Projects
                </span>
                <h1 class="mt-4 text-3xl font-extrabold leading-tight text-navy-dark sm:text-4xl lg:text-5xl">Sponsor an Orphan Today</h1>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Select a child below to see their story. We offer two ways to help: choose <strong class="text-navy-dark">monthly
                    support</strong> to cover their ongoing educational and living expenses, or give a one-off gift to
                    contribute to their urgent needs. <strong class="text-navy-dark">100% of your donation reaches them.</strong>
                </p>
                <a href="#orphans" class="btn-brand mt-7 px-7 py-3">
                    Sponsor an orphan
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

            {{-- Right: animated video --}}
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'orphans-sponsorships'])
            </div>
        </div>
    </section>

    {{-- ===================== ORPHAN GRID (AJAX pagination) ===================== --}}
    <section id="orphans" class="py-14 sm:py-16" data-ajax-pagination>
        <div class="nf-container">
            <div class="nf-reveal mb-9 text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Every child deserves a chance</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">Children Waiting for a Sponsor</h2>
            </div>

            {{-- This container is swapped in place on page changes — no reload. --}}
            <div data-orphan-list>
                @include('partials.orphan-list')
            </div>
        </div>
    </section>

    {{-- ===================== OUR PROJECTS ===================== --}}
    <section class="bg-cream/40 py-16 sm:py-20">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Our programmes</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Our Projects</h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-gray-500 sm:text-base">
                    Naeem Foundation is a vibrant and compassionate NGO dedicated to improving the lives of individuals
                    and communities in need, with a solid spotlight on compassion.
                </p>
            </div>

            <div class="mt-10">
                @include('partials.projects-carousel')
            </div>
        </div>
    </section>

@endsection
