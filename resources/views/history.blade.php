@extends('layouts.app')

@section('title', 'Our History — ' . config('app.name'))

{{-- Light hero → keep the header solid. --}}
@section('header-solid', 'yes')

@php
    // "Our work creates lasting changes" value cards
    $features = [
        ['no' => '01', 'title' => 'Meaningful Impact', 'text' => 'Every project we run directly improves the lives of individuals and communities in need.'],
        ['no' => '02', 'title' => 'Community First', 'text' => 'We grew from a small group of volunteers into a community-led movement built on compassion.'],
        ['no' => '03', 'title' => 'Continuous Growth', 'text' => 'Year after year we have expanded our reach — from food and water to education and healthcare.'],
        ['no' => '04', 'title' => 'Trust & Transparency', 'text' => 'We are committed to honesty and accountability in everything we do for our supporters.'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO ===================== --}}
    <section class="bg-cream">
        <div class="nf-container">
            <div class="grid items-center gap-10 py-12 lg:grid-cols-2 lg:gap-14 lg:py-16">
                {{-- Text --}}
                <div class="nf-reveal">
                    <p class="inline-block border-b-2 border-brand pb-1 text-sm font-semibold text-brand">Who We Are</p>
                    <h1 class="mt-4 text-4xl font-extrabold leading-[1.1] text-navy sm:text-5xl">
                        Our History
                    </h1>
                    <p class="mt-5 max-w-md text-sm leading-relaxed text-gray-600 sm:text-base">
                        Naeem Foundation began with a simple belief — that compassion, when shared, can change lives.
                        What started as a small group of volunteers has grown into a community-led movement dedicated to
                        improving the lives of individuals and communities in need.
                    </p>
                    <a href="{{ route('about') }}" class="btn-navy mt-6">Learn More About Us</a>
                </div>

                {{-- Image --}}
                <div>
                    <img src="{{ asset('images/about us hero banner.png') }}" alt="Naeem Foundation team"
                         class="h-64 w-full rounded-xl object-cover shadow-md sm:h-80 lg:h-[360px]">
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== NAVY BANNER CARD ===================== --}}
    <section class="py-12 sm:py-14">
        <div class="nf-container">
            <div class="overflow-hidden rounded-2xl bg-navy">
                <div class="grid items-center gap-6 lg:grid-cols-2">
                    {{-- Text --}}
                    <div class="p-8 lg:p-10">
                        <p class="text-sm font-semibold text-amber-400">Our Journey</p>
                        <h2 class="mt-2 text-2xl font-bold leading-snug text-white sm:text-3xl">
                            Our work creates lasting<br class="hidden sm:block"> changes
                        </h2>
                        <p class="mt-4 max-w-lg text-sm leading-relaxed text-white/80">
                            From our earliest days, we have believed in the power of compassion and community development
                            to address pressing social challenges. Over the years, that belief has carried us from a
                            handful of local relief efforts to wide-reaching programmes across food, water, education and
                            healthcare.
                        </p>
                        <a href="{{ route('donate.checkout') }}" class="btn-brand mt-6">Donate Now</a>
                    </div>

                    {{-- Image --}}
                    <div class="p-5 lg:py-6 lg:pr-6">
                        <img src="{{ asset('images/zakatcenter.png') }}" alt="Naeem Foundation in the community"
                             class="h-52 w-full rounded-xl object-cover sm:h-60 lg:h-64">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== VALUE CARDS ===================== --}}
    <section class="pb-16">
        <div class="nf-container">
            <p class="text-sm font-semibold text-brand">What drives us</p>
            <h2 class="mt-1 text-2xl font-bold leading-snug text-navy sm:text-3xl">The values behind our story</h2>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($features as $i => $feature)
                    <div class="nf-reveal rounded-lg bg-cream p-6" style="transition-delay: {{ $i * 100 }}ms">
                        <span class="block text-4xl font-extrabold leading-none text-slate-300">{{ $feature['no'] }}</span>
                        <h3 class="mt-3 font-bold leading-snug text-navy">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $feature['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
