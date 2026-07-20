@extends('layouts.app')

@section('title', 'Cambodia Education & Welfare — ' . config('app.name'))

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
        ['title' => 'Access to Education', 'text' => 'Helping Cambodian children continue their studies without financial barriers.'],
        ['title' => 'Supporting Teachers', 'text' => 'Providing essential assistance to educators who guide and inspire young minds.'],
        ['title' => 'Healthy Learning Environments', 'text' => 'Ensuring students have nutritious meals, clean water, and safe classrooms.'],
        ['title' => 'Community Upliftment', 'text' => 'Improving school facilities, libraries, and learning resources for long-lasting impact.'],
    ];

    // Gallery inside the cream panel
    $gallery = [
        ['image' => 'images/changinslives1.jpg', 'alt' => 'Volunteers at a Cambodian school'],
        ['image' => 'images/changinslives2.jpg', 'alt' => 'Feeding future scholars distribution'],
        ['image' => 'images/changinslives3.jpg', 'alt' => 'Students in a Cambodian classroom'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives1.jpg',
        'heroEyebrow' => 'Appeals',
        'heroTitle' => 'Empowering Young Minds, Creating Brighter Futures in <span class="text-cream">Cambodia</span>',
        'heroSubtitle' => 'Education is more than a classroom experience — it is the foundation of dignity, opportunity and hope. In Cambodia, many children struggle to attend school due to poverty, lack of meals, clean water and safe learning environments.',
        'widgetCauses' => ['Cambodia Education & Welfare', 'School Meals', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            {{-- Left: text --}}
            <div>
                <p class="text-sm leading-relaxed text-gray-600 sm:text-base">
                    In Cambodia, many schools operate without adequate classrooms, reliable access to clean water, or
                    sufficient learning materials. Students often attend lessons hungry, while teachers work with minimal
                    resources and limited support. These challenges force many children to miss school or drop out
                    entirely, limiting their chances of a stable future. Addressing these gaps is essential to ensure
                    children can learn, teachers can teach effectively, and communities can progress sustainably.
                </p>

                <h2 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Our Focus in Cambodia</h2>
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
            </div>

            {{-- Right: animated video --}}
            <div>
                @include('partials.video-card', ['videoKey' => 'cambodia-education-welfare'])
            </div>
        </div>
    </section>

    {{-- ===================== EDUCATING CAMBODIA'S FUTURE (cream) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:px-12">
                <div class="mx-auto max-w-2xl text-center">
                    <h3 class="text-xl font-bold text-navy-dark sm:text-2xl">Join Us in Educating Cambodia’s Future</h3>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                        Your donation can make a real difference in the lives of Cambodian children. By supporting
                        education and welfare in Cambodia, you help students stay enrolled, teachers teach effectively,
                        and communities grow stronger. Together, we can turn challenges into opportunities and build
                        brighter futures for young minds.
                    </p>
                </div>

                {{-- Gallery --}}
                <div class="mt-8 grid gap-5 sm:grid-cols-3">
                    @foreach ($gallery as $i => $shot)
                        <div class="nf-reveal group overflow-hidden rounded-xl" data-reveal-delay="{{ $i * 120 }}">
                            <img src="{{ asset($shot['image']) }}" alt="{{ $shot['alt'] }}"
                                 class="h-44 w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105 sm:h-40 lg:h-44">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== OUR PROJECTS (dynamic carousel) ===================== --}}
    <section class="pb-16 sm:pb-20">
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
