@extends('layouts.app')

@section('title', 'Hostel for Students & Orphans — ' . config('app.name'))

@php
    // "Our Projects" carousel — real admin projects when present, else these defaults.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Hostel & Care', 'description' => 'A safe home, warm meals and study support for orphans and students.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives3.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    // Monthly sponsorship levels (currency follows the visitor's region).
    $levels = [
        ['amount' => 45, 'label' => 'A Safe Bed', 'note' => 'A clean bed, bedding and a secure roof for one child each month.'],
        ['amount' => 75, 'label' => 'Full Board', 'note' => 'Accommodation plus three nutritious meals a day.'],
        ['amount' => 120, 'label' => 'Complete Care', 'note' => 'Full board, schooling, guardianship and everyday essentials.'],
    ];

    // What each sponsorship makes possible.
    $provides = [
        ['title' => 'Safe Accommodation', 'text' => 'A secure, supervised place to live, away from harm and hardship.', 'icon' => '<path d="M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Nutritious Meals', 'text' => 'Three balanced meals a day so no child ever studies on an empty stomach.', 'icon' => '<path d="M4 3v18M4 8h4M8 3v18M14 3c-1.5 2-1.5 5 0 7v11M18 3c1.5 2 1.5 5 0 7" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Study & Learning Space', 'text' => 'Quiet rooms, books and mentoring to help every student thrive.', 'icon' => '<path d="M4 19V5a2 2 0 0 1 2-2h9l5 5v11M4 19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2M9 8h4M9 12h6" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Loving Guardianship', 'text' => 'Caring wardens and mentors who guide, protect and nurture each child.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Health & Wellbeing', 'text' => 'Regular check-ups, medicine and a safe space to grow up healthy.', 'icon' => '<path d="M4 12h4l2 5 4-10 2 5h4" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Clothing & Essentials', 'text' => 'Seasonal clothing, uniforms and daily necessities provided year round.', 'icon' => '<path d="M8 3 3 7l3 3 2-1v11h8V9l2 1 3-3-5-4-2 2a3 3 0 0 1-4 0L8 3z" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives4.jpg',
        'heroEyebrow' => 'Projects',
        'heroTitle' => 'A Safe Home to <span class="text-cream">Learn</span> & Grow',
        'heroSubtitle' => 'Our hostels give orphans and students from far-off communities a secure home, warm meals and the support they need to complete their education with dignity.',
        'widgetCauses' => ['Hostel Sponsorship', 'Orphan Care', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO QUOTE ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container nf-reveal mx-auto max-w-3xl text-center">
            <p class="text-lg font-bold italic text-brand sm:text-xl">&ldquo;A safe home is the foundation of a bright future.&rdquo;</p>
            <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                For many orphans and students, the biggest barrier to learning isn&rsquo;t ability &mdash; it&rsquo;s having
                nowhere safe to stay. Children from distant villages are often forced to abandon their studies simply
                because there is no home near their school or madrasah. At Naeem Foundation, our hostels remove that
                barrier: a warm bed, wholesome food, a place to study and caring guardianship, so every child can stay,
                learn and flourish.
            </p>
        </div>
    </section>

    {{-- ===================== WHAT YOUR SUPPORT PROVIDES ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="nf-reveal text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Everything under one roof</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">What Your Support Provides</h2>
            </div>

            <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($provides as $i => $p)
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

    {{-- ===================== SPONSORSHIP LEVELS ===================== --}}
    <section class="pb-14">
        <div class="nf-container grid items-start gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: levels --}}
            <div class="nf-reveal">
                <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">Sponsor a Child&rsquo;s Place</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    A small monthly gift keeps a vulnerable child sheltered, fed and in education all year round. Choose a
                    level that&rsquo;s right for you:
                </p>

                <div class="mt-5 space-y-3">
                    @foreach ($levels as $l)
                        <div class="flex items-center gap-4 rounded-xl border border-brand/15 bg-cream/50 p-4 transition-colors hover:border-brand/40">
                            <span class="grid h-14 w-14 shrink-0 place-items-center rounded-full bg-brand text-sm font-extrabold text-white">{{ money($l['amount'], 0) }}</span>
                            <div>
                                <p class="font-bold text-navy-dark">{{ $l['label'] }} <span class="text-xs font-medium text-gray-400">/ month</span></p>
                                <p class="text-xs leading-relaxed text-gray-500">{{ $l['note'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="mt-5 text-xs leading-relaxed text-gray-400">
                    Prefer a one-off gift? Any amount helps cover beds, meals and repairs &mdash; use the form above to give
                    whatever you can.
                </p>
            </div>

            {{-- Right: image + "join us" card --}}
            <div class="nf-reveal lg:sticky lg:top-28" data-reveal-delay="120">
                <div class="overflow-hidden rounded-2xl bg-cream shadow-sm ring-1 ring-navy/10">
                    <div class="relative h-64 overflow-hidden sm:h-80">
                        <img src="{{ asset('images/changinslives2.jpg') }}" alt="Children at the hostel" class="h-full w-full object-cover">
                        <span class="absolute inset-0 bg-gradient-to-t from-navy-dark/40 to-transparent"></span>
                    </div>
                    <div class="p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-navy-dark">Join Us in Making a Difference</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            When you sponsor a place in our hostel, you don&rsquo;t just give a child a roof &mdash; you give
                            them safety, belonging and the chance to build a future they can be proud of.
                        </p>
                        <a href="#donate" class="btn-brand mt-5 px-6 py-2.5">
                            Sponsor a Place
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== WHO WE HELP (navy CTA) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Give shelter, give a future</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Be the Home They Never Had</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Orphans who have lost everything, and students who&rsquo;ve travelled miles from home, deserve more than a
                place to sleep &mdash; they deserve to feel safe, valued and hopeful. Your generosity turns an empty bed
                into a home, and a struggling child into a confident young leader.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                Sponsor a Place
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
