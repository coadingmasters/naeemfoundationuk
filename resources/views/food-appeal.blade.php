@extends('layouts.app')

@section('title', 'Food Appeal — ' . config('app.name'))

@php
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives3.jpg', 'title' => 'Binoria Water Campaign', 'description' => 'Water crisis hit Jamia Binoria hard — students struggle for clean water.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    // What the appeal delivers.
    $achieved = [
        ['title' => 'Nutritious Food Packages', 'text' => 'Balanced packs of rice, lentils, oil, flour and other essentials for families.', 'icon' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Hygiene Kits', 'text' => 'Soap, sanitiser, masks and personal care items to keep families healthy.', 'icon' => '<path d="M4 12h4l2 5 4-10 2 5h4" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Local Suppliers', 'text' => 'We source locally where possible — feeding families and the local economy.', 'icon' => '<path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Targeted Outreach', 'text' => 'Working with local leaders to reach the families who need it most.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];

    $atWork = [
        ['title' => 'Feeding Families', 'text' => 'Bulk, discounted purchasing means every pound provides more meals for more families.'],
        ['title' => 'Supporting Communities', 'text' => 'Buying from local suppliers strengthens the very communities we serve.'],
        ['title' => 'Health & Wellbeing', 'text' => 'Nutritious food and hygiene essentials protect families from illness and hardship.'],
        ['title' => 'Lasting Change', 'text' => 'Every gift creates a ripple of change towards a future free from hunger.'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives2.jpg',
        'heroEyebrow' => 'Appeals',
        'heroTitle' => 'No One Should Go to <span class="text-cream">Bed Hungry</span>',
        'heroSubtitle' => 'Support our mission to provide nutritious food and daily essentials to the most vulnerable families in our community.',
        'widgetCauses' => ['Food Appeal', 'Ration Pack', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO + VIDEO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            {{-- Left: text --}}
            <div class="nf-reveal">
                <p class="text-lg font-bold italic text-brand sm:text-xl">&ldquo;Feed the hungry, and you feed the soul.&rdquo;</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    At Naeem Foundation, we believe that no one should go to bed hungry. Our Food Appeal is a cornerstone
                    of our efforts to support the most vulnerable members of our community.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Through this initiative, we provide essential food items and necessities to those in need &mdash;
                    ensuring families have access to nutritious meals and basic essentials, with dignity and care.
                </p>
            </div>
            {{-- Right: animated video --}}
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'food-appeal'])
            </div>
        </div>
    </section>

    {{-- ===================== WHAT WE'VE ACHIEVED ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="nf-reveal text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Your support in action</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">What We&rsquo;ve Achieved</h2>
            </div>

            <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($achieved as $i => $p)
                    <div class="nf-reveal flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-navy/10"
                         data-reveal-delay="{{ $i * 60 }}">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $p['icon'] !!}</svg>
                        </span>
                        <h3 class="mt-4 text-base font-bold text-navy-dark">{{ $p['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $p['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== DONATIONS AT WORK ===================== --}}
    <section class="pb-14">
        <div class="nf-container grid items-start gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: impact --}}
            <div class="nf-reveal">
                <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">Your Donations at Work</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    When you give to our Food Appeal, you make a direct and lasting impact on the lives of those in need:
                </p>
                <ul class="mt-5 space-y-4">
                    @foreach ($atWork as $a)
                        <li class="flex gap-3">
                            <span class="mt-1 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-brand/10 text-brand">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                            <p class="text-sm leading-relaxed text-gray-600 sm:text-base"><span class="font-bold text-navy-dark">{{ $a['title'] }}:</span> {{ $a['text'] }}</p>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-7 rounded-xl border border-brand/15 bg-cream/50 p-5">
                    <h4 class="text-sm font-bold text-navy-dark">Ways to give</h4>
                    <ul class="mt-2 space-y-1.5 text-sm text-gray-600">
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span> Single gifts — {{ region('symbol') }}50, {{ region('symbol') }}100, {{ region('symbol') }}250 or any amount.</li>
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span> Monthly or weekly giving to sustain families all year round.</li>
                    </ul>
                </div>
            </div>

            {{-- Right: image + "join us" card --}}
            <div class="nf-reveal lg:sticky lg:top-28" data-reveal-delay="120">
                <div class="overflow-hidden rounded-2xl bg-cream shadow-sm ring-1 ring-navy/10">
                    <div class="relative h-64 overflow-hidden sm:h-80">
                        <img src="{{ asset('images/changinslives2.jpg') }}" alt="Distributing food packages" class="h-full w-full object-cover">
                        <span class="absolute inset-0 bg-gradient-to-t from-navy-dark/40 to-transparent"></span>
                    </div>
                    <div class="p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-navy-dark">Join Us in Making a Difference</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            A small contribution goes a long way in ensuring a family has food on the table. Together, we
                            can work towards a future where no one has to worry about their next meal.
                        </p>
                        <a href="#donate" class="btn-brand mt-5 px-6 py-2.5">
                            Feed a Family
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA (navy) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Every meal matters</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Put Food on an Empty Table</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                For a family facing hunger, your donation is more than a meal &mdash; it&rsquo;s relief, dignity and hope.
                Give today and help us make sure no one in our community goes without.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                Feed a Family
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

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
