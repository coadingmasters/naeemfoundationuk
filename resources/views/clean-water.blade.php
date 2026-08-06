@extends('layouts.app')

@section('title', 'Clean Water — ' . config('app.name'))

@php
    // "Our Projects" carousel — real admin projects when present, else these defaults.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/handpump.jpg', 'title' => 'Clean Water', 'description' => 'Hand pumps, wells and filtration bringing safe water to communities in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/handpump.jpg',
        'heroEyebrow' => 'Projects',
        'heroTitle' => 'Clean Water, <span class="text-cream">Changed</span> Lives',
        'heroSubtitle' => 'Millions still live without safe water to drink. Your gift funds hand pumps, wells and filtration that bring clean, life-saving water to communities in need.',
        'widgetCauses' => ['Clean Water', 'Water Pump', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO + VIDEO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div class="nf-reveal">
                <p class="text-lg font-bold italic text-brand sm:text-xl">&ldquo;Water is life &mdash; and every drop of it is a lifeline.&rdquo;</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    For countless families, clean water is still a daily struggle. Women and children walk miles under
                    the sun to reach a source that is often unsafe, and preventable waterborne disease continues to take
                    young lives.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    At Naeem Foundation, we install hand pumps, dig wells and set up filtration to give communities a
                    safe, sustainable source they can rely on for years to come.
                </p>
            </div>
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'clean-water'])
            </div>
        </div>
    </section>

    {{-- ===================== STATS BAND ===================== --}}
    @include('partials.give.stats', [
        'variant' => 'navy',
        'eyebrow' => 'The water crisis',
        'title' => 'Why clean water can’t wait',
        'stats' => [
            ['num' => '2.2bn', 'label' => 'people live without safely managed water'],
            ['num' => '1 in 5', 'label' => 'child deaths linked to unsafe water'],
            ['num' => '6 km', 'label' => 'average daily walk to collect water'],
            ['num' => '100%', 'label' => 'of your gift reaches the project'],
        ],
    ])

    {{-- ===================== DONATION TIERS ===================== --}}
    @include('partials.give.tiers', [
        'eyebrow' => 'Choose your gift',
        'title' => 'Fund a Water Source',
        'intro' => 'A one-off gift can leave a lasting legacy of clean water — a Sadaqah Jariyah that keeps giving long after your donation.',
        'tiers' => [
            ['amount' => 150, 'label' => 'A Hand Pump', 'note' => 'A dedicated hand pump giving one family a safe, lasting water source.'],
            ['amount' => 350, 'label' => 'A Family Well', 'note' => 'A shallow well serving several families in a small community.', 'featured' => true],
            ['amount' => 1500, 'label' => 'A Community Well', 'note' => 'A deep well that can serve an entire village for years to come.'],
        ],
    ])

    {{-- ===================== IMAGE HIGHLIGHT ===================== --}}
    @include('partials.give.highlight', [
        'variant' => 'image',
        'image' => 'images/handpump.jpg',
        'reverse' => true,
        'eyebrow' => 'A gift that keeps giving',
        'title' => 'One well, a whole village transformed',
        'body' => 'Clean water changes everything — health, education and hope. When you give, you don’t just quench a thirst; you change the future of an entire community.',
        'points' => [
            'Safe water at the doorstep — no more dangerous journeys',
            'Children back in school instead of fetching water',
            'Families freed from preventable waterborne disease',
        ],
    ])

    {{-- ===================== OUR PROJECTS ===================== --}}
    <section class="py-16 sm:py-20">
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
