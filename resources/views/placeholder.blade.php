@extends('layouts.app')

@section('title', ($pageTitle ?? 'Page') . ' — ' . config('app.name'))

@section('content')
    {{-- Hero banner --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-navy via-navy-dark to-brand">
        <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-32 -left-20 h-96 w-96 rounded-full bg-brand/30 blur-3xl"></div>

        <div class="nf-container relative z-10 py-16 text-center text-white sm:py-20">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider">
                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                {{ $pageTag ?? 'Naeem Foundation' }}
            </span>
            <h1 class="mt-4 text-4xl font-extrabold leading-tight sm:text-5xl">{{ $pageTitle ?? 'Page' }}</h1>

            <nav class="mt-4 flex items-center justify-center gap-2 text-sm text-white/70">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span>/</span>
                <span class="text-white">{{ $pageTitle ?? 'Page' }}</span>
            </nav>
        </div>
    </section>

    {{-- Body --}}
    <section class="py-16 sm:py-24">
        <div class="nf-container">
            <div class="nf-reveal mx-auto max-w-2xl text-center">
                <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-cream text-brand">
                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                        <path d="M12 8v4l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <h2 class="mt-6 text-2xl font-bold text-navy-dark sm:text-3xl">This page is coming soon</h2>
                <p class="mt-3 text-base leading-relaxed text-gray-600">
                    {{ $pageText ?? 'We are working on this page and it will be available shortly.' }}
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('home') }}" class="btn-navy">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M11 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Back to Home
                    </a>
                    <a href="#" class="btn-brand">
                        Donate
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
