@extends('layouts.app')

@section('title', 'Sadaqah Jariyah — ' . config('app.name'))

@php
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/handpump.jpg', 'title' => 'Clean Water', 'description' => 'Hand pumps and wells bringing safe water to communities in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
        ]);
    }

    $forms = [
        ['title' => 'Water Wells & Pumps', 'text' => 'Clean water for a whole community, flowing for years to come.', 'icon' => '<path d="M12 2s6 7 6 12a6 6 0 0 1-12 0c0-5 6-12 6-12z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Education', 'text' => 'Teaching a child to read benefits them and everyone they teach.', 'icon' => '<path d="M4 19V5a2 2 0 0 1 2-2h9l5 5v11M9 8h4M9 12h6" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Orphan Care', 'text' => 'Lasting support that raises a child into a confident, capable adult.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Livelihoods', 'text' => 'Tools and training that lift a family out of poverty for good.', 'icon' => '<path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];
@endphp

@section('content')

    @include('partials.donate-hero', [
        'heroImage' => 'images/handpump.jpg',
        'heroEyebrow' => 'Islamic Giving',
        'heroTitle' => 'A Gift That <span class="text-cream">Never Stops</span> Giving',
        'heroSubtitle' => 'Sadaqah Jariyah is an ongoing charity that keeps rewarding you long after you give — and even after you pass away, Insha’Allah.',
        'widgetCauses' => ['Sadaqah Jariyah', 'Water Well', 'Where Most Needed'],
    ])

    {{-- INTRO + VIDEO --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div class="nf-reveal">
                <p class="text-lg font-bold italic text-brand sm:text-xl">&ldquo;When a person dies, their deeds end &mdash; except three: a continuing charity…&rdquo; (Muslim)</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Sadaqah Jariyah &mdash; &lsquo;ongoing charity&rsquo; &mdash; is any good deed that keeps benefiting people
                    over time. A well that quenches thirst for decades, a child taught to read, a livelihood that lifts a
                    whole family: each keeps earning reward for the giver long after the gift is made.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    It is one of the most beautiful ways to give &mdash; a legacy of goodness in your name, or in memory of
                    a loved one who has passed.
                </p>
            </div>
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'sadaqah-jariyah'])
            </div>
        </div>
    </section>

    {{-- FORMS OF SADAQAH JARIYAH --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="nf-reveal text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Leave a legacy</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">Ways to Give Sadaqah Jariyah</h2>
            </div>
            <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($forms as $i => $p)
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

    {{-- CTA --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Endless reward</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Plant a Seed That Keeps Growing</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Give a Sadaqah Jariyah today and let your generosity flow for years to come &mdash; a source of reward for
                you and relief for those in need, long into the future.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">Give Sadaqah Jariyah
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        </div>
    </section>

    {{-- OUR PROJECTS --}}
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
