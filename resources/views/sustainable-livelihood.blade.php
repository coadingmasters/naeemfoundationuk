@extends('layouts.app')

@section('title', 'Sustainable Livelihood — ' . config('app.name'))

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
        ['title' => 'Microfinance', 'text' => 'Providing access to small loans and financial services to aspiring entrepreneurs.'],
        ['title' => 'Vocational Training', 'text' => 'Equipping individuals with the skills needed to succeed in various industries.'],
        ['title' => 'Entrepreneurship Development', 'text' => 'Promoting a culture of innovation and self-reliance.'],
        ['title' => 'Small Business Support', 'text' => 'Offering mentorship, training, and market access to help entrepreneurs thrive.'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/givesadqa.jpg',
        'heroEyebrow' => 'Appeals',
        'heroTitle' => 'Breaking Free from <span class="text-cream">Poverty</span>',
        'heroSubtitle' => 'In a world where unemployment is a pressing concern, we are committed to providing sustainable livelihood opportunities — empowering individuals with the tools and resources to build a better life for themselves and their families.',
        'widgetCauses' => ['Sustainable Livelihood', 'Microfinance', 'Vocational Training', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: text --}}
            <div>
                <p class="text-lg font-bold italic text-brand sm:text-xl">“Empowerment begins with opportunity.”</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Poverty is more than just a lack of money; it’s the absence of opportunity. But with the right support,
                    anyone can break free from the cycle of poverty and build a better future for themselves and their
                    families. At Naeem Foundation, we believe that everyone deserves a chance to succeed. By providing
                    tools, training, and financial support, we’re helping individuals transform their lives and achieve
                    economic independence. Together, we can empower people to dream bigger and reach higher.
                </p>

                <h2 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Our focus</h2>
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
                @include('partials.video-card', ['videoKey' => 'sustainable-livelihood'])
            </div>
        </div>
    </section>

    {{-- ===================== SUSTAINABLE FUTURE (cream) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:px-12">
                <img src="{{ asset('images/zakatcenter.png') }}" alt="Communities supported by Naeem Foundation"
                     class="h-56 w-full rounded-xl object-cover sm:h-72 lg:h-80">

                <h3 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Join Us in Creating a Sustainable Future</h3>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Let’s work together to create a world where everyone has the chance to reach their full potential.
                    Your support will help us equip individuals with the tools they need to create a sustainable
                    livelihood, empowering them to break the cycle of poverty.
                </p>

                <div class="mt-8 border-t border-brand/15 pt-6">
                    <h4 class="text-base font-bold text-navy-dark">Donation Options</h4>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600 sm:text-base">
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Single Donations:</span> Suggested amounts (e.g. {{ region('symbol') }}30, {{ region('symbol') }}50, {{ region('symbol') }}100, {{ region('symbol') }}250).</span></li>
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Recurring Donations:</span> Sustain a family’s income month after month.</span></li>
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Currency Options:</span> PKR, GBP, USD, CAD, EUR.</span></li>
                    </ul>
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

    {{-- ===================== GET INVOLVED (CTA) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Get involved</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Join Us in Empowering Our Community</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Your support is vital in helping us provide essential services to those in need. By contributing, you are
                investing in the well-being of individuals and strengthening the bonds that unite our community. Let’s
                work together to create a community where no one feels alone or forgotten.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                Make a donation
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

@endsection
