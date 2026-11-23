{{-- One of Canada's local project pages. $project = 'youth' | 'food' | 'women'
     (content: config/local_projects.php). --}}
@extends('layouts.app')

@php
    $p = config('local_projects.'.$project);
    abort_unless($p, 404);
@endphp

@section('title', $p['title'].' — '.config('app.name'))

@section('content')

    @include('partials.donate-hero', [
        'heroImage' => $p['image'],
        'heroEyebrow' => 'Local Projects',
        'heroTitle' => $p['heading'],
        'heroSubtitle' => $p['short'],
        'widgetCauses' => [$p['cause'], 'Where Most Needed'],
    ])

    {{-- INTRO --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container max-w-4xl">
            <div class="nf-reveal text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Local Project</p>
                <h1 class="mt-2 text-2xl font-extrabold text-navy-dark sm:text-3xl lg:text-4xl">{{ $p['title'] }}</h1>
                <p class="mt-5 text-lg font-bold italic text-brand sm:text-xl">&ldquo;{{ $p['quote'] }}&rdquo;</p>
                @foreach ($p['intro'] as $para)
                    <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">{{ $para }}</p>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FOCUS --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="nf-reveal text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">In our community</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">{{ $p['focus_heading'] }}</h2>
            </div>
            <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($p['focus'] as $i => $f)
                    <div class="nf-reveal flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-navy/10" data-reveal-delay="{{ $i * 60 }}">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $f['icon'] !!}</svg>
                        </span>
                        <h3 class="mt-4 text-base font-bold text-navy-dark">{{ $f['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $f['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">{{ $p['cta_eyebrow'] }}</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">{{ $p['cta_title'] }}</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">{{ $p['cta_text'] }}</p>
            <div class="mt-7 flex flex-wrap items-center justify-center gap-3">
                <a href="#donate" class="btn-brand px-7 py-3">Donate Now
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
                <a href="{{ route('volunteer') }}" class="inline-flex items-center gap-2 rounded-full border border-white/40 px-7 py-3 text-sm font-semibold text-white transition hover:bg-white/10">Volunteer With Us</a>
            </div>
        </div>
    </section>

@endsection
