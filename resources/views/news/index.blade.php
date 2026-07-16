@extends('layouts.app')

@section('title', 'News & Press — ' . config('app.name'))

{{-- Light hero → keep the header solid. --}}
@section('header-solid', 'yes')

@php
    // Category pill colours
    $tone = fn ($c) => match ($c) {
        'Press Release' => 'bg-navy text-white',
        'Emergency' => 'bg-brand text-white',
        'Blog' => 'bg-cream text-brand',
        default => 'bg-brand/10 text-brand',
    };
@endphp

@section('content')

    {{-- ===================== TITLE BAND ===================== --}}
    <section class="bg-cream">
        <div class="nf-container py-12 text-center sm:py-16">
            <p class="text-sm font-semibold uppercase tracking-wider text-brand">Who We Are</p>
            <h1 class="mt-2 text-3xl font-extrabold text-navy sm:text-4xl lg:text-5xl">News &amp; Press</h1>
            <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 sm:text-base">
                The latest updates, press releases and stories from the field — straight from the communities your
                generosity supports.
            </p>
        </div>
    </section>

    {{-- ===================== FILTERS ===================== --}}
    <section class="border-b border-gray-100 bg-white">
        <div class="nf-container flex flex-wrap items-center justify-center gap-2 py-5">
            <a href="{{ route('news') }}"
               class="nf-chip {{ $active ? '' : 'is-active' }}">All</a>
            @foreach ($categories as $c)
                <a href="{{ route('news', ['category' => $c]) }}"
                   class="nf-chip {{ $active === $c ? 'is-active' : '' }}">{{ $c }}</a>
            @endforeach
        </div>
    </section>

    {{-- ===================== POSTS ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container">

            @if (! $featured && $posts->isEmpty())
                <div class="mx-auto max-w-lg rounded-2xl border border-dashed border-brand/30 bg-cream/50 p-10 text-center">
                    <span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-brand/10 text-brand">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5h13v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" stroke-linejoin="round"/><path d="M17 9h2a1 1 0 0 1 1 1v8a2 2 0 0 1-2 2M8 9h6M8 13h6M8 17h3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    <h2 class="mt-4 text-lg font-bold text-navy-dark">
                        {{ $active ? 'Nothing here yet' : 'Stories coming soon' }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ $active ? 'No posts in this category right now.' : 'Our latest news and press releases will appear here.' }}
                    </p>
                    @if ($active)
                        <a href="{{ route('news') }}" class="btn-brand mt-5">View all stories</a>
                    @endif
                </div>
            @else

                {{-- ===== Lead story ===== --}}
                @if ($featured)
                    <a href="{{ route('news.show', $featured) }}"
                       class="nf-reveal group mb-14 grid items-center gap-8 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-xl lg:grid-cols-2 lg:gap-0">
                        <div class="relative h-64 overflow-hidden lg:h-full lg:min-h-[22rem]">
                            @if ($featured->image_url)
                                <img src="{{ $featured->image_url }}" alt="{{ $featured->title }}"
                                     class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @else
                                <div class="h-full w-full bg-gradient-to-br from-navy to-navy-dark"></div>
                            @endif
                            <span class="absolute left-4 top-4 rounded-full px-3 py-1 text-xs font-bold shadow {{ $tone($featured->category) }}">
                                {{ $featured->category }}
                            </span>
                        </div>

                        <div class="p-6 sm:p-8 lg:p-10">
                            <p class="text-xs font-semibold uppercase tracking-wider text-brand">Featured story</p>
                            <h2 class="mt-2 text-2xl font-extrabold leading-snug text-navy-dark sm:text-3xl">
                                {{ $featured->title }}
                            </h2>
                            <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">{{ $featured->teaser }}</p>

                            <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500">
                                @if ($featured->published_at)
                                    <span>{{ $featured->published_at->format('j F Y') }}</span>
                                    <span class="hidden h-1 w-1 rounded-full bg-gray-300 sm:block"></span>
                                @endif
                                <span>{{ $featured->read_minutes }} min read</span>
                            </div>

                            <span class="btn-brand mt-6">
                                Read the story
                                <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                        </div>
                    </a>
                @endif

                {{-- ===== Grid ===== --}}
                @if ($posts->isNotEmpty())
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($posts as $i => $post)
                            <a href="{{ route('news.show', $post) }}"
                               class="nf-reveal group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                               style="transition-delay: {{ ($i % 3) * 90 }}ms">

                                <div class="relative h-48 overflow-hidden">
                                    @if ($post->image_url)
                                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="h-full w-full bg-gradient-to-br from-navy to-navy-dark"></div>
                                    @endif
                                    <span class="absolute left-3 top-3 rounded-full px-3 py-1 text-xs font-bold shadow {{ $tone($post->category) }}">
                                        {{ $post->category }}
                                    </span>
                                </div>

                                <div class="flex flex-1 flex-col p-5">
                                    <h3 class="text-base font-bold leading-snug text-navy-dark transition-colors group-hover:text-brand">
                                        {{ $post->title }}
                                    </h3>
                                    <p class="mt-2 flex-1 text-sm leading-relaxed text-gray-600">
                                        {{ \Illuminate\Support\Str::limit($post->teaser, 110) }}
                                    </p>

                                    <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3 text-xs text-gray-500">
                                        <span>{{ $post->published_at?->format('j M Y') ?? '' }}</span>
                                        <span class="inline-flex items-center gap-1 font-semibold text-brand">
                                            Read more
                                            <svg class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </section>

    {{-- ===================== PRESS CONTACT ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Media enquiries</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Working on a story?</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Our press team can provide interviews, imagery, case studies and data. We aim to respond to all media
                enquiries within one working day.
            </p>
            <a href="mailto:Contact@naeemfoundation.co.uk" class="btn-brand mt-7 px-7 py-3">
                Contact the press team
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

@endsection
