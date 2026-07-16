@extends('layouts.app')

@section('title', $post->title . ' — ' . config('app.name'))

{{-- Light hero → keep the header solid. --}}
@section('header-solid', 'yes')

@php
    $tone = fn ($c) => match ($c) {
        'Press Release' => 'bg-navy text-white',
        'Emergency' => 'bg-brand text-white',
        'Blog' => 'bg-cream text-brand',
        default => 'bg-brand/10 text-brand',
    };
@endphp

@section('content')

    {{-- ===================== ARTICLE HEAD ===================== --}}
    <section class="bg-cream">
        <div class="nf-container py-12 sm:py-16">
            <nav aria-label="Breadcrumb" class="text-xs text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-brand">Home</a>
                <span class="mx-1.5">›</span>
                <a href="{{ route('news') }}" class="hover:text-brand">News &amp; Press</a>
            </nav>

            <div class="mt-4 max-w-3xl">
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $tone($post->category) }}">
                    {{ $post->category }}
                </span>

                <h1 class="mt-4 text-3xl font-extrabold leading-tight text-navy sm:text-4xl lg:text-5xl">
                    {{ $post->title }}
                </h1>

                <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-500">
                    @if ($post->published_at)
                        <span>{{ $post->published_at->format('j F Y') }}</span>
                        <span class="hidden h-1 w-1 rounded-full bg-gray-300 sm:block"></span>
                    @endif
                    <span>{{ $post->read_minutes }} min read</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== ARTICLE BODY ===================== --}}
    <article class="py-12 sm:py-16">
        <div class="nf-container">

            @if ($post->image_url)
                <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                     class="nf-reveal mx-auto max-w-4xl rounded-2xl object-cover shadow-lg">
            @endif

            <div class="mx-auto mt-10 max-w-3xl">
                @if ($post->excerpt)
                    <p class="border-l-4 border-brand pl-5 text-base font-semibold leading-relaxed text-navy-dark sm:text-lg">
                        {{ $post->excerpt }}
                    </p>
                @endif

                {{-- Body: plain text, paragraph per blank line. Escaped for safety. --}}
                <div class="mt-8 space-y-5 text-sm leading-relaxed text-gray-700 sm:text-base">
                    @forelse (preg_split('/\R{2,}/', trim((string) $post->body)) as $para)
                        @if (trim($para) !== '')
                            <p>{!! nl2br(e(trim($para))) !!}</p>
                        @endif
                    @empty
                    @endforelse
                </div>

                {{-- Share + back --}}
                <div class="mt-10 flex flex-wrap items-center justify-between gap-4 border-t border-gray-100 pt-6">
                    <a href="{{ route('news') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand hover:text-navy">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M11 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Back to all stories
                    </a>

                    <a href="{{ route('donate.checkout') }}" class="btn-brand">
                        Support our work
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </article>

    {{-- ===================== RELATED ===================== --}}
    @if ($related->isNotEmpty())
        <section class="bg-cream/60 py-14 sm:py-16">
            <div class="nf-container">
                <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">More stories</h2>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $i => $item)
                        <a href="{{ route('news.show', $item) }}"
                           class="nf-reveal group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
                           style="transition-delay: {{ $i * 90 }}ms">
                            <div class="relative h-40 overflow-hidden">
                                @if ($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}"
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="h-full w-full bg-gradient-to-br from-navy to-navy-dark"></div>
                                @endif
                                <span class="absolute left-3 top-3 rounded-full px-2.5 py-0.5 text-[11px] font-bold shadow {{ $tone($item->category) }}">
                                    {{ $item->category }}
                                </span>
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <h3 class="text-sm font-bold leading-snug text-navy-dark transition-colors group-hover:text-brand">
                                    {{ $item->title }}
                                </h3>
                                <p class="mt-2 flex-1 text-xs leading-relaxed text-gray-500">
                                    {{ \Illuminate\Support\Str::limit($item->teaser, 90) }}
                                </p>
                                <span class="mt-3 text-xs text-gray-400">{{ $item->published_at?->format('j M Y') }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
