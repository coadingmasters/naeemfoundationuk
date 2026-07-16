@extends('layouts.app')

@section('title', 'Prosthetic Limb — ' . config('app.name'))

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

    // Two-up galleries
    $introGallery = [
        ['image' => 'images/supporton.png', 'alt' => 'Fitting a prosthetic limb'],
        ['image' => 'images/changinslives4.jpg', 'alt' => 'A child raising their hand after treatment'],
    ];

    $creamGallery = [
        ['image' => 'images/changinslives1.jpg', 'alt' => 'A child standing with a new prosthetic limb'],
        ['image' => 'images/changinslives4.jpg', 'alt' => 'Rehabilitation support session'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives4.jpg',
        'heroEyebrow' => 'Appeals',
        'heroTitle' => 'Give the Gift of Mobility <span class="text-cream">Restore Lives</span>',
        'heroSubtitle' => 'A prosthetic limb restores far more than movement — it restores independence, confidence and the ability to earn a livelihood.',
        'widgetCauses' => ['Prosthetic Limb', 'Medical Care', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-start gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: copy --}}
            <div>
                <h2 class="text-xl font-bold leading-snug text-navy-dark sm:text-2xl">
                    Every Step Counts, Help Someone Walk Again
                </h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    For many people, losing a limb means losing far more than physical movement. It affects independence,
                    confidence, and the ability to earn a livelihood. Everyday tasks we take for granted — walking,
                    standing, and working — become difficult challenges. With your support, we can restore mobility and
                    give them the freedom to live life fully.
                </p>

                <h2 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Restoring Hope and Dignity</h2>
                <p class="mt-2 text-sm font-bold italic text-brand">“A Limb Today, A Life Transformed Forever”</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                    A prosthetic limb does more than help someone walk. It restores dignity, confidence, and hope. With
                    proper support, a person can return to work, provide for their family, and rebuild their future. Your
                    donation directly transforms a life by replacing hardship with strength and opportunity.
                </p>
            </div>

            {{-- Right: animated video --}}
            <div>
                @include('partials.video-card', ['videoKey' => 'prosthetic-limb'])
            </div>
        </div>
    </section>

    {{-- ===================== DETAIL PANEL (cream) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:px-12">

                {{-- Why your support matters --}}
                <h3 class="text-xl font-bold text-navy-dark sm:text-2xl">Why Your Support Matters</h3>
                <p class="mt-2 text-sm font-bold italic text-brand">“Change a Life with Every Step”</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Providing a prosthetic limb allows someone to regain independence and actively participate in society.
                    Children can play and attend school, while adults can work and contribute to their families and
                    communities. Each limb is a step toward a brighter, more hopeful future.
                </p>

                {{-- Our focus --}}
                <h3 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Our Focus</h3>
                <p class="mt-2 text-sm font-bold italic text-brand">“Ensuring Every Limb Comes with Care”</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                    This appeal provides complete prosthetic limbs, including medical fitting, adjustment, and
                    rehabilitation support. Every donation ensures recipients not only receive a limb, but also the
                    guidance needed to use it effectively and safely.
                </p>

                {{-- Donation details --}}
                <h3 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Donation Details</h3>
                <p class="mt-2 text-sm font-bold italic text-brand">“Minimum Target: One Limb, Maximum Impact”</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                    The cost of <span class="font-bold text-navy-dark">one complete prosthetic limb is £1,200</span>.
                    Our goal is to raise <span class="font-bold text-navy-dark">£12,000</span>, which will provide
                    <span class="font-bold text-navy-dark">10 life-changing prosthetic limbs</span>. By contributing, you
                    make a direct and tangible difference in the lives of those in need.
                </p>

                {{-- Gallery --}}
                <div class="mt-8 grid gap-5 sm:grid-cols-2">
                    @foreach ($creamGallery as $i => $shot)
                        <div class="nf-reveal group overflow-hidden rounded-xl" style="transition-delay: {{ $i * 120 }}ms">
                            <img src="{{ asset($shot['image']) }}" alt="{{ $shot['alt'] }}"
                                 class="h-64 w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105 sm:h-72 lg:h-80">
                        </div>
                    @endforeach
                </div>

                {{-- Your impact --}}
                <h3 class="mt-9 text-xl font-bold text-navy-dark sm:text-2xl">Your Impact</h3>
                <p class="mt-2 text-sm font-bold italic text-brand">“From Hardship to Hope”</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Your support restores independence and dignity. With every prosthetic limb provided, a person can walk
                    again, return to work, and actively engage with their family and community. Your contribution
                    transforms lives and creates lasting change.
                </p>

                {{-- Make a difference --}}
                <h3 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Your Donation Can Make a Difference</h3>
                <p class="mt-2 text-sm font-bold italic text-brand">“Step Forward, Be the Reason Someone Walks Again”</p>
                <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Every contribution brings us closer to providing a complete prosthetic limb to someone in need. Your
                    generosity can restore mobility, rebuild confidence, and give a person the chance to live life
                    independently and with dignity.
                </p>

                <div class="mt-8 border-t border-brand/15 pt-6">
                    <a href="#donate" class="btn-brand px-7 py-3">
                        Donate a Limb
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
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
