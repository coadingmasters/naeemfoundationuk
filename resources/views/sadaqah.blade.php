@extends('layouts.app')

@section('title', 'Sadaqah — ' . config('app.name'))

@php
    // "Our Projects" cards — managed in the admin dashboard.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food Support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives3.jpg', 'title' => 'Binoria Water Campaign', 'description' => 'Water Crisis Hit Jamia Binoria Hard — students struggle for clean water.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => 'Helping children in rural areas access quality education.', 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    // Forms of Sadaqah (icon = single-path SVG).
    $forms = [
        ['title' => 'Providing Water', 'text' => 'The joy of a thirsty person receiving cool water on a hot day.', 'icon' => '<path d="M12 3s6 6 6 10a6 6 0 0 1-12 0c0-4 6-10 6-10z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Helping Others', 'text' => 'Lending a hand, a kind word, or removing a stone from the road.', 'icon' => '<path d="M20.8 6.6a5 5 0 0 0-7.1 0L12 8.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 22l8.8-8.3a5 5 0 0 0 0-7.1z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Feeding the Hungry', 'text' => 'Providing food to those who are hungry — among the most rewarding.', 'icon' => '<path d="M4 11h16a8 8 0 0 1-8 8 8 8 0 0 1-8-8zM12 3v4" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Clothing the Needy', 'text' => 'Giving clothes brings warmth and dignity to those in need.', 'icon' => '<path d="M16 3l5 3-2.5 4L16 9v11H8V9l-2.5 1L3 6l5-3 4 2 4-2z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Offering Shelter', 'text' => 'Helping someone find shelter or a place to stay.', 'icon' => '<path d="M3 11l9-7 9 7M5 10v10h14V10" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Teaching Knowledge', 'text' => 'Sharing beneficial knowledge is an ongoing charity.', 'icon' => '<path d="M12 6C10 4.5 7 4.5 5 6v12c2-1.5 5-1.5 7 0 2-1.5 5-1.5 7 0V6c-2-1.5-5-1.5-7 0z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Protecting the Environment', 'text' => 'Planting a tree or caring for animals is also Sadaqah.', 'icon' => '<path d="M11 21c-4-1-7-4.5-7-9 0-3 5-9 7-9s7 6 7 9c0 4.5-3 8-7 9Zm0 0v-9" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Forgiving Someone', 'text' => 'Letting go of a grudge and forgiving is an emotional Sadaqah.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden">
        <img src="{{ asset('images/givesadqa.jpg') }}" alt="" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-dark via-brand/85 to-brand/60"></div>
        <div class="absolute inset-0 bg-navy-dark/25"></div>

        <div class="nf-container relative py-16 sm:py-20 lg:py-24">
            <div class="max-w-2xl text-white nf-reveal">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider ring-1 ring-white/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                    Sadaqah
                </span>
                <h1 class="mt-5 text-4xl font-extrabold leading-[1.05] sm:text-5xl lg:text-6xl">
                    Sadaqah: The<br>Power of <span class="text-cream">Giving</span>
                </h1>
                <p class="mt-4 max-w-xl text-base leading-relaxed text-white/85 sm:text-lg">
                    A voluntary act of charity that brings endless blessings — give for the love of Allah (SWT) and
                    help make the world a better place.
                </p>
                <a href="#donate" class="btn-white mt-7 px-7 py-3">
                    Donate Now
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>

                <nav class="mt-6 flex items-center gap-2 text-sm text-white/70">
                    <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                    <span>/</span>
                    <span class="text-white">Sadaqah</span>
                </nav>
            </div>
        </div>
    </section>

    {{-- ===================== WHAT IS SADAQAH + DONATE ===================== --}}
    <section id="donate" class="py-14 sm:py-16">
        <div class="nf-container grid gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left --}}
            <div>
                <p class="inline-block border-b-2 border-brand pb-1 text-sm font-semibold text-brand">What is Sadaqah?</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Sadaqah is more than just giving money. It's any good deed done with sincerity: feeding the hungry,
                    helping a neighbour, supporting an orphan, or simply smiling at someone who needs it. The Prophet
                    Muhammad (PBUH) taught us that even the smallest of actions — when done with kindness — can be
                    considered Sadaqah.
                </p>

                <figure class="mt-6 border-l-4 border-brand bg-cream/60 p-5">
                    <blockquote class="text-sm font-semibold italic text-navy-dark sm:text-base">“Your smile for your brother is charity.”</blockquote>
                    <figcaption class="mt-2 text-xs text-gray-500">— Jami' at-Tirmidhi 1956</figcaption>
                </figure>

                <figure class="mt-4 border-l-4 border-brand bg-cream/60 p-5">
                    <blockquote class="text-sm font-semibold italic text-navy-dark sm:text-base">“Give Sadaqah without delay, for it stands in the way of calamity.”</blockquote>
                    <figcaption class="mt-2 text-xs text-gray-500">— Mishkat al-Masabih 1887</figcaption>
                </figure>
            </div>

            {{-- Right --}}
            <div class="lg:pl-2">
                @include('partials.donate-widget', ['widgetCauses' => ['Sadaqah', 'Zakat', 'Where Most Needed']])
            </div>
        </div>
    </section>

    {{-- ===================== WHY / HOW (cream) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:px-12">
                <div class="grid gap-10 lg:grid-cols-2">
                    <div class="nf-reveal">
                        <h2 class="text-2xl font-bold text-navy-dark">Why Give Sadaqah?</h2>
                        <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                            Giving Sadaqah is a powerful way to protect ourselves from harm and bring blessings into our
                            lives. Think of Sadaqah as a protective shield — a means of keeping difficulties away. When we
                            give, even doors to Allah's mercy open, and no matter how big or small, becomes blessed.
                        </p>
                        <div class="mt-4 rounded-xl border border-brand/15 bg-white p-4">
                            <p class="text-sm leading-relaxed text-gray-700">“The example of those who spend their wealth in the way of Allah is like a seed that sprouts seven ears; in every ear is a hundred grains. And Allah multiplies for whom He wills.”</p>
                            <p class="mt-2 text-xs font-semibold text-brand">— Qur'an 2:261</p>
                        </div>
                    </div>

                    <div class="nf-reveal">
                        <h2 class="text-2xl font-bold text-navy-dark">How Does Sadaqah Benefit Us?</h2>
                        <ul class="mt-3 space-y-3 text-sm text-gray-600 sm:text-base">
                            <li class="flex gap-2"><span class="font-bold text-brand">›</span><span><span class="font-semibold text-navy-dark">Cleanses our wealth:</span> it purifies our income, making it more fruitful and beneficial.</span></li>
                            <li class="flex gap-2"><span class="font-bold text-brand">›</span><span><span class="font-semibold text-navy-dark">Cures illness:</span> “Treat your sick ones with charity.”</span></li>
                            <li class="flex gap-2"><span class="font-bold text-brand">›</span><span><span class="font-semibold text-navy-dark">Multiplies rewards:</span> every penny, every effort, is multiplied by Allah (SWT) — like a ripple effect of goodness.</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FORMS OF SADAQAH ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Every act counts</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Forms of Sadaqah</h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-gray-500 sm:text-base">
                    The beauty of Sadaqah lies in its many forms — it isn't limited to wealth. It's about making a
                    difference in any way you can.
                </p>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($forms as $form)
                    <div class="nf-reveal flex h-full flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-cream text-brand">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">{!! $form['icon'] !!}</svg>
                        </span>
                        <h3 class="mt-4 font-bold text-navy-dark">{{ $form['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $form['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== A LASTING IMPACT (CTA) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">A lasting impact</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Give Sadaqah Today</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Sadaqah not only supports those in need, it creates a cycle of goodness and hope. Whether it's a small
                amount or a large contribution, every bit counts — and brings you closer to Allah (SWT).
            </p>
            <div class="mx-auto mt-5 max-w-xl rounded-xl bg-white/5 p-4 ring-1 ring-white/10">
                <p class="text-sm leading-relaxed text-white/85">“Who is it that will lend Allah a goodly loan so He may multiply it for him many times over?”</p>
                <p class="mt-2 text-xs font-semibold text-[#e9b9c6]">— Qur'an 2:245</p>
            </div>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                Give Sadaqah
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

    {{-- ===================== OUR PROJECTS (dynamic) ===================== --}}
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

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($projects as $project)
                    <div class="group flex h-full flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:shadow-lg">
                        <div class="overflow-hidden">
                            <img src="{{ asset($project->image) }}" alt="{{ $project->title }}"
                                 class="h-48 w-full object-cover transition duration-500 group-hover:scale-105">
                        </div>
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="font-bold text-brand">{{ $project->title }}</h3>
                            <p class="mt-1.5 line-clamp-2 flex-1 text-sm text-gray-500">{{ $project->description }}</p>
                            <a href="{{ $project->link ?: '#' }}" class="btn-brand mt-4 w-full py-2.5">Donate Now</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
