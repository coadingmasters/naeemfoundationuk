@extends('layouts.app')

@section('title', 'Food & Sustenance — ' . config('app.name'))

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
        ['title' => 'Empowering through Nutrition', 'text' => 'Providing essential sustenance to fuel lives and aspirations.'],
        ['title' => 'Bridging the Hunger Gap', 'text' => 'Ensuring access to nutritious meals for vulnerable communities.'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives2.jpg',
        'heroEyebrow' => 'Appeals',
        'heroTitle' => 'A Family <span class="text-cream">Fed</span> is a Family Empowered.',
        'heroSubtitle' => 'In a world where access to food is a basic human right, we at Naeem Foundation are committed to ensuring that no one goes hungry — providing direct and dignified support to those facing food insecurity.',
        'widgetCauses' => ['Food & Sustenance', 'Ration Pack', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: text --}}
            <div>
                <p class="text-lg font-bold italic text-brand sm:text-xl">“A meal today brings hope for tomorrow.”</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Hunger is not just the absence of food — it’s the absence of hope. For families struggling to put
                    meals on the table, the future can feel uncertain and overwhelming. At Naeem Foundation, we believe
                    that no one should have to go hungry. Every meal we provide brings warmth, dignity, and a renewed
                    sense of hope to those in need. By coming together, we can turn hunger into hope and help families
                    not just survive, but thrive.
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

            {{-- Right: image --}}
            <div>
                <img src="{{ asset('images/changinslives2.jpg') }}" alt="Food distribution to families in need"
                     class="h-72 w-full rounded-2xl object-cover shadow-md sm:h-80 lg:h-[420px]">
            </div>
        </div>
    </section>

    {{-- ===================== JOIN US (cream) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:px-12">
                <h3 class="text-xl font-bold text-navy-dark sm:text-2xl">Join Us in Nourishing Lives</h3>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Your generous donation can make a tangible difference in the lives of those struggling to put food on
                    the table. By supporting our food assistance programs, you are helping to alleviate hunger, promote
                    health, and empower individuals to build a better future.
                </p>

                <div class="mt-8 border-t border-brand/15 pt-6">
                    <h4 class="text-base font-bold text-navy-dark">Donation Options</h4>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600 sm:text-base">
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Single Donations:</span> Suggested amounts (e.g. £30, £50, £100, £250).</span></li>
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Recurring Donations:</span> Set up a monthly contribution to feed a family every month.</span></li>
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
                No child should sleep hungry. Your donation today puts food on a family’s table and restores their
                dignity. Together, we can turn hunger into hope.
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
