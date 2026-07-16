@extends('layouts.app')

@section('title', 'Ramadan Food Packs — ' . config('app.name'))

@php
    // "Our Projects" cards — managed in the admin dashboard.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food Support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives3.jpg', 'title' => 'Binoria Water Campaign', 'description' => 'Water Crisis Hit Jamia Binoria Hard — students struggle for clean water.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => 'Empowering tomorrow’s leaders today at Naeem Foundation.', 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    // What's inside a food pack — icon = single-path SVG.
    $contents = [
        ['title' => 'Flour, Rice & Grains', 'text' => 'The staples that fill the plate — enough to see a family through the month of fasting.', 'icon' => '<path d="M4 11h16a8 8 0 0 1-8 8 8 8 0 0 1-8-8zM12 3v4" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Lentils & Pulses', 'text' => 'Protein-rich dhal and beans to keep families nourished and strong during Ramadan.', 'icon' => '<path d="M12 3s6 6 6 10a6 6 0 0 1-12 0c0-4 6-10 6-10z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Oil, Ghee & Sugar', 'text' => 'Cooking essentials to prepare a warm, wholesome iftar to break the fast each evening.', 'icon' => '<path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Dates & Essentials', 'text' => 'Dates to open the fast in the Sunnah way, plus tea, salt and everyday necessities.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives2.jpg',
        'heroEyebrow' => 'Ramadan Food Packs',
        'heroTitle' => 'Share Your Ramadan Blessings — <span class="text-cream">Feed a Family</span>',
        'heroSubtitle' => 'No one should break their fast on an empty table. A single food pack provides a family with the essentials to eat with dignity throughout the blessed month.',
        'widgetCauses' => ['Ramadan Food Packs', 'Zakat', 'Where Most Needed'],
    ])

    {{-- ===================== WHAT IS A FOOD PACK ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid gap-10 lg:grid-cols-2 lg:items-center lg:gap-14">
            <div>
                <p class="inline-block border-b-2 border-brand pb-1 text-sm font-semibold text-brand">A month of mercy</p>
                <h2 class="mt-4 text-2xl font-bold text-navy-dark sm:text-3xl">One Pack. A Whole Month of Meals.</h2>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Ramadan is a time of reflection, worship and generosity. Yet for families living in poverty, each
                    day of fasting is shadowed by the worry of whether there will be food to break it. A Naeem Foundation
                    food pack removes that worry — providing the staple foods a family needs to observe Ramadan with
                    peace and dignity.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Every pack is carefully assembled with nutritious, long-lasting essentials and hand-delivered to
                    widows, orphans and struggling families who need it most.
                </p>

                <div class="mt-6 space-y-4">
                <figure class="border-l-4 border-brand bg-cream/60 p-5">
                    <blockquote class="text-sm font-semibold italic text-navy-dark sm:text-base">“Whoever feeds a fasting person will have a reward like that of the fasting person, without any reduction in his reward.”</blockquote>
                    <figcaption class="mt-2 text-xs text-gray-500">— Jami' at-Tirmidhi 807</figcaption>
                </figure>
                <figure class="border-l-4 border-brand bg-cream/60 p-5">
                    <blockquote class="text-sm font-semibold italic text-navy-dark sm:text-base">“The best charity is that given in Ramadan.”</blockquote>
                    <figcaption class="mt-2 text-xs text-gray-500">— Jami' at-Tirmidhi 663</figcaption>
                </figure>
                </div>
            </div>

            {{-- Right: animated video --}}
            <div>
                @include('partials.video-card', ['videoKey' => 'ramadan-food-packs'])
            </div>
        </div>
    </section>

    {{-- ===================== WHAT'S INSIDE ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Packed with care</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">What’s Inside a Food Pack</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-500 sm:text-base">
                    Each pack is filled with wholesome, culturally-appropriate staples — enough to sustain a family for
                    the whole of Ramadan.
                </p>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($contents as $item)
                    <div class="nf-reveal flex h-full flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-cream text-brand">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">{!! $item['icon'] !!}</svg>
                        </span>
                        <h3 class="mt-4 font-bold text-navy-dark">{{ $item['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== YOUR IMPACT (navy band) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Your impact</p>
                <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Every Pack Changes a Ramadan</h2>
            </div>
            <div class="mt-8 grid gap-5 sm:grid-cols-3">
                <div class="rounded-2xl bg-white/5 p-6 text-center ring-1 ring-white/10">
                    <p class="text-4xl font-extrabold text-white">£40</p>
                    <p class="mt-1 text-sm text-white/70">feeds one family for the entire month</p>
                </div>
                <div class="rounded-2xl bg-white/5 p-6 text-center ring-1 ring-white/10">
                    <p class="text-4xl font-extrabold text-white">30</p>
                    <p class="mt-1 text-sm text-white/70">days of iftar and suhoor covered per pack</p>
                </div>
                <div class="rounded-2xl bg-white/5 p-6 text-center ring-1 ring-white/10">
                    <p class="text-4xl font-extrabold text-white">100%</p>
                    <p class="mt-1 text-sm text-white/70">of your donation reaches families in need</p>
                </div>
            </div>
            <div class="mt-8 text-center">
                <a href="#donate" class="btn-brand px-7 py-3">
                    Give a Food Pack
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== STORY ===================== --}}
    <section class="py-16">
        <div class="nf-container">
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="grid lg:grid-cols-2">
                    {{-- Image --}}
                    <div class="relative min-h-[280px] lg:min-h-full">
                        <img src="{{ asset('images/changinslives3.jpg') }}" alt="A family receiving a Ramadan food pack"
                             class="absolute inset-0 h-full w-full object-cover">
                        <span class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-brand px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-white shadow-lg">
                            <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                            From the field
                        </span>
                    </div>

                    {{-- Text --}}
                    <div class="p-6 sm:p-10">
                        <h2 class="text-2xl font-bold text-brand">A Table No Longer Empty</h2>
                        <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                            For families who have lost everything, the arrival of a food pack is a moment of relief and
                            gratitude — the reassurance that this Ramadan, their children will not go to sleep hungry.
                        </p>

                        <figure class="mt-6 rounded-xl border-l-4 border-brand bg-cream/60 p-5">
                            <blockquote class="text-sm leading-relaxed italic text-navy-dark sm:text-base">
                                “Before, I did not know how I would feed my children at iftar. When the food pack arrived,
                                I wept. May Allah reward everyone who made this possible — you have carried a heavy burden
                                from my shoulders.”
                            </blockquote>
                            <figcaption class="mt-3 text-xs font-semibold text-gray-500">— A grateful mother of four</figcaption>
                        </figure>

                        <a href="#donate" class="btn-navy mt-6 px-7 py-2.5">
                            Feed a Family This Ramadan
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA BANNER ===================== --}}
    <section class="pb-16">
        <div class="nf-container">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-navy via-navy to-navy-dark px-6 py-12 text-center sm:px-10">
                <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-brand/25 blur-3xl"></div>
                <div class="pointer-events-none absolute -left-16 -bottom-16 h-56 w-56 rounded-full bg-white/5 blur-3xl"></div>
                <div class="relative">
                    <h2 class="text-2xl font-bold text-white sm:text-3xl">Multiply Your Rewards This Ramadan</h2>
                    <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                        Give a food pack and share in the reward of every fast it helps a family keep. Add Gift Aid as a
                        UK taxpayer and increase your donation by 25% — at no extra cost to you.
                    </p>
                    <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                        Donate a Food Pack
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== OUR PROJECTS (dynamic) ===================== --}}
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
