@extends('layouts.app')

@section('title', 'Healthcare — ' . config('app.name'))

@php
    // "Our Projects" cards — managed in the admin dashboard, with a resilient fallback.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food Support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives3.jpg', 'title' => 'Binoria Water Campaign', 'description' => 'Water Crisis Hit Jamia Binoria Hard — students struggle for clean water.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => 'Empowering tomorrow’s leaders today at Naeem Foundation.', 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    // Focus areas
    $focus = [
        ['title' => 'Telemedicine', 'text' => 'Providing virtual consultations and medical guidance to bridge the gap between health experts and individuals in remote areas.'],
        ['title' => 'Medical Clinics', 'text' => 'Establishing accessible healthcare facilities in rural communities.'],
        ['title' => 'Comprehensive Care', 'text' => 'Offering a wide range of services, from preventive care to treatment and rehabilitation.'],
        ['title' => 'Health Education', 'text' => 'Promoting healthy lifestyles and disease prevention through education and awareness programs.'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives4.jpg',
        'heroEyebrow' => 'Appeals',
        'heroTitle' => 'Health is a Basic <span class="text-cream">Human</span> Right',
        'heroSubtitle' => 'In rural areas, access to healthcare is often limited. At Naeem Foundation, we are committed to breaking down these barriers and ensuring everyone has access to quality medical care.',
        'widgetCauses' => ['Healthcare', 'Medical Camps', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: text --}}
            <div>
                <p class="text-lg font-bold italic text-brand sm:text-xl">“Health is the foundation of a thriving life.”</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Health is not a luxury — it’s a basic human right. Yet, for many living in rural and underserved areas,
                    access to healthcare can feel like an impossible dream. At Naeem Foundation, we are committed to
                    ensuring that everyone, no matter where they live, has access to the care they need. Through innovative
                    solutions and compassionate outreach, we’re working to bring life-saving healthcare to the doorsteps of
                    those who need it most. Together, we can make health and well-being a reality for all.
                </p>
            </div>

            {{-- Right: animated video --}}
            <div>
                @include('partials.video-card', ['videoKey' => 'healthcare'])
            </div>
        </div>
    </section>

    {{-- ===================== FOCUS + JOIN (cream) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:px-12">

                {{-- Our focus --}}
                <h3 class="text-xl font-bold text-navy-dark sm:text-2xl">Our focus</h3>
                <ul class="mt-4 space-y-4">
                    @foreach ($focus as $item)
                        <li class="flex gap-3">
                            <span class="mt-1 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-brand/10 text-brand">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                            <p class="text-sm leading-relaxed text-gray-600 sm:text-base">
                                <span class="font-bold text-navy-dark">{{ $item['title'] }}:</span> {{ $item['text'] }}
                            </p>
                        </li>
                    @endforeach
                </ul>

                {{-- Join us --}}
                <h3 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Join Us in Building a Healthier Community</h3>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Your generosity can be a lifeline for those in need. By supporting our healthcare initiatives, you’re
                    helping to save lives, improve health outcomes, and build a healthier, more compassionate community.
                </p>

                <div class="mt-8 border-t border-brand/15 pt-6">
                    <h4 class="text-base font-bold text-navy-dark">Donation Options</h4>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600 sm:text-base">
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Single Donations:</span> Suggested amounts (e.g. £30, £50, £100, £250).</span></li>
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Recurring Donations:</span> Set up a monthly contribution to sustain ongoing care.</span></li>
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Currency Options:</span> PKR, GBP, USD, CAD, EUR.</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== BE THE CHANGE (CTA) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Give your support</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Be the Change They Need</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Your donation today can be a lifeline — providing medicine, treatment and hope to those who need it most.
                Together, we can ensure that quality healthcare reaches every community.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                Support the Cause
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

    {{-- ===================== OUR PROJECTS (dynamic carousel) ===================== --}}
    <section class="py-16 sm:py-20">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Livelihood programs</p>
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
