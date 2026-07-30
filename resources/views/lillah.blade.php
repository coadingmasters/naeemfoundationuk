@extends('layouts.app')

@section('title', 'Lillah — ' . config('app.name'))

@php
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/handpump.jpg', 'title' => 'Clean Water', 'description' => 'Hand pumps and wells bringing safe water to communities in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    $uses = [
        ['title' => 'Building & Facilities', 'text' => 'Schools, hostels and community centres that Zakat cannot always fund.', 'icon' => '<path d="M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Running Costs', 'text' => 'Keeping our projects open — utilities, staff and day-to-day essentials.', 'icon' => '<circle cx="12" cy="12" r="3"/><path d="M19 12a7 7 0 0 0-.1-1l2-1.5-2-3.5-2.4 1a7 7 0 0 0-1.7-1L16 3H8l-.8 3a7 7 0 0 0-1.7 1l-2.4-1-2 3.5 2 1.5a7 7 0 0 0 0 2l-2 1.5 2 3.5 2.4-1a7 7 0 0 0 1.7 1L8 21h8l.8-3a7 7 0 0 0 1.7-1l2.4 1 2-3.5-2-1.5a7 7 0 0 0 .1-1z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'General Relief', 'text' => 'Wherever the need is greatest, without the conditions Zakat requires.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Anyone in Need', 'text' => 'Lillah can help all people in hardship, Muslim or non-Muslim.', 'icon' => '<path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4 0-8 2-8 5v1h16v-1c0-3-4-5-8-5z" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];
@endphp

@section('content')

    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives1.jpg',
        'heroEyebrow' => 'Islamic Giving',
        'heroTitle' => 'Give Purely for the <span class="text-cream">Sake of Allah</span>',
        'heroSubtitle' => 'Lillah is a voluntary gift given solely for Allah’s pleasure. Free of the conditions of Zakat, it can be used wherever the need is greatest.',
        'widgetCauses' => ['Lillah', 'Where Most Needed'],
    ])

    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div class="nf-reveal">
                <p class="text-lg font-bold italic text-brand sm:text-xl">&ldquo;Give, and it shall be given to you.&rdquo;</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Lillah simply means &lsquo;for Allah&rsquo;. It is a general, voluntary charity given with no expectation
                    of return &mdash; purely to seek His pleasure and reward.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Unlike Zakat, Lillah is not bound by strict rules on who may receive it, so it can fund the buildings,
                    running costs and wider relief that keep our work alive &mdash; helping anyone in need, wherever they are.
                </p>
            </div>
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'lillah'])
            </div>
        </div>
    </section>

    <section class="pb-14">
        <div class="nf-container">
            <div class="nf-reveal text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Where it helps</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">How Your Lillah Is Used</h2>
            </div>
            <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($uses as $i => $p)
                    <div class="nf-reveal flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-navy/10" data-reveal-delay="{{ $i * 60 }}">
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

    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Purely for Him</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Give With an Open Heart</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Your Lillah keeps our schools open, our wells flowing and our hands reaching the most vulnerable. Give
                today, purely for the sake of Allah, and share in the reward of every life you touch.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">Give Lillah
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        </div>
    </section>

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
            <div class="mt-10">@include('partials.projects-carousel')</div>
        </div>
    </section>

@endsection
