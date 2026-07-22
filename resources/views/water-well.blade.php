@extends('layouts.app')

@section('title', 'Water Pumps — ' . config('app.name'))

@php
    // "Our Projects" cards — managed in the admin dashboard.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food Support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives3.jpg', 'title' => 'Binoria Water Campaign', 'description' => 'Water Crisis Hit Jamia Binoria Hard — students struggle for clean water.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => 'Helping children in rural areas access quality education.', 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    $filtration = [
        ['title' => 'Physical Filtration', 'text' => 'Water passes through sand, gravel and charcoal filters to remove visible impurities like dirt and debris.'],
        ['title' => 'Chemical Filtration', 'text' => 'Chemicals are added to help particles clump together, making them easier to remove.'],
        ['title' => 'Sedimentation', 'text' => 'Water sits to allow larger particles to settle at the bottom.'],
        ['title' => 'Activated Carbon Filtration', 'text' => 'Smaller particles and contaminants are removed by passing through activated carbon.'],
        ['title' => 'Disinfection', 'text' => 'The water is treated with chlorine or UV light to kill remaining bacteria and viruses.'],
    ];

    $impact = [
        ['title' => 'Health', 'text' => 'Prevents diseases like cholera and diarrhea.'],
        ['title' => 'Education', 'text' => 'Children can attend school instead of fetching water.'],
        ['title' => 'Economic Development', 'text' => 'Communities can grow businesses and gardens without health hindrances.'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/handpump.jpg',
        'heroEyebrow' => 'Appeals',
        'heroTitle' => 'Water <span class="text-cream">Pumps</span>',
        'heroSubtitle' => 'Support our mission to provide clean, safe water for people in need.',
        'widgetCauses' => ['Water Pump', 'Water Well', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: text --}}
            <div>
                <p class="text-sm leading-relaxed text-gray-600 sm:text-base">
                    At Naeem Foundation, we recognise the fundamental importance of clean and accessible water for
                    communities in need. Through our dedicated efforts, we have implemented crucial arrangements to
                    provide sustainable water solutions to those who need it most.
                </p>

                <h2 class="mt-6 text-2xl font-bold text-navy-dark sm:text-3xl">Water Pump Facilities</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    One of our primary initiatives involves the installation of water pump facilities in underserved
                    areas. These pumps are strategically placed to ensure easy access to clean water for families and
                    communities. By installing these pumps, we aim to alleviate the burden on individuals — especially
                    women and children — who often have to travel long distances to fetch water.
                </p>
            </div>

            {{-- Right: animated video --}}
            <div>
                @include('partials.video-card', ['videoKey' => 'water-well'])
            </div>
        </div>
    </section>

    {{-- ===================== FILTRATION + IMPACT (cream) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:px-12">
                <img src="{{ asset('images/handpump.jpg') }}" alt="Clean water project"
                     class="h-56 w-full rounded-xl object-cover sm:h-72 lg:h-80">

                {{-- Filtration --}}
                <h3 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Our Water Filtration Process</h3>
                <ul class="mt-4 space-y-4">
                    @foreach ($filtration as $step)
                        <li class="flex gap-3">
                            <span class="mt-1 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-brand/10 text-brand">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                            <p class="text-sm leading-relaxed text-gray-600 sm:text-base">
                                <span class="font-bold text-navy-dark">{{ $step['title'] }}:</span> {{ $step['text'] }}
                            </p>
                        </li>
                    @endforeach
                </ul>

                {{-- Impact --}}
                <h3 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Impact</h3>
                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                    @foreach ($impact as $i)
                        <div class="rounded-xl border border-brand/10 bg-white p-5">
                            <h4 class="font-bold text-brand">{{ $i['title'] }}</h4>
                            <p class="mt-1.5 text-sm leading-relaxed text-gray-600">{{ $i['text'] }}</p>
                        </div>
                    @endforeach
                </div>

                {{-- Join us --}}
                <h3 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Join Us in Making a Difference</h3>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Your donation today can transform lives. Together, we can continue to provide sustainable water
                    solutions to communities in need. Whether it's a one-time contribution or a recurring donation, every
                    amount makes a significant impact.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Let's work together to ensure that no one has to struggle for something as basic as clean water. Join
                    us in our mission to create a world where every person has access to this essential resource.
                </p>

                <div class="mt-8 border-t border-brand/15 pt-6">
                    <h4 class="text-base font-bold text-navy-dark">Donation Options</h4>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600 sm:text-base">
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Single Donations:</span> Suggested amounts (e.g. {{ region('symbol') }}50, {{ region('symbol') }}100, {{ region('symbol') }}250, {{ region('symbol') }}500).</span></li>
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Recurring Donations:</span> Set up a monthly contribution to sustain ongoing projects.</span></li>
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Currency Options:</span> PKR, GBP, USD, CAD, EUR.</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== BE THE CHANGE (CTA) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Give your time</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Be the Change They Need</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Together, we can turn this crisis around. Your donation today not only provides clean water but also
                restores hope and fuels the dreams of future leaders. Join us in ensuring that communities remain a
                place of growth, learning and unity.
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
