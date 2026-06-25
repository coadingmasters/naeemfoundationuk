@extends('layouts.app')

@section('title', config('app.name') . ' — Changing Lives')

@php
    // Hero slides (duplicated until real images are supplied)
    $heroSlides = [
        ['image' => 'images/homepagehero.png', 'title' => 'YOUR ZAKAT<br>CAN SAVE THEM'],
        ['image' => 'images/homepagehero.png', 'title' => 'GIVE HOPE<br>THIS RAMADAN'],
        ['image' => 'images/homepagehero.png', 'title' => 'TOGETHER WE<br>CHANGE LIVES'],
    ];

    // Latest Appeals cards
    $appeals = [
        ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'text' => 'Sadaqah: The Power of Giving. Have you ever felt the true…'],
        ['image' => 'images/changinslives2.jpg', 'title' => 'Food & Sustenance', 'text' => 'Food Support. Our Mission to Provide for People in Need. Donate…'],
        ['image' => 'images/changinslives3.jpg', 'title' => 'Binoria Water', 'text' => 'Water Crisis Hit Jamia Binoria Hard. Students Struggle even for a…'],
        ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'text' => 'In rural areas, access to healthcare is often limited. At Naeem Foundation…'],
        ['image' => 'images/changinslives2.jpg', 'title' => 'Ramadan Food Packs', 'text' => 'Provide a month of meals for a family in need this Ramadan…'],
        ['image' => 'images/changinslives4.jpg', 'title' => 'Medical Camps', 'text' => 'Free check-ups and medicine for remote communities. Support today…'],
        ['image' => 'images/changinslives1.jpg', 'title' => 'Orphan Sponsorship', 'text' => 'Give an orphan shelter, food and education every single month…'],
        ['image' => 'images/changinslives3.jpg', 'title' => 'Clean Water Wells', 'text' => 'Fund a well and bring clean water to an entire village…'],
    ];

    // Group appeals into pages of 4 for the carousel
    $appealPages = array_chunk($appeals, 4);

    // Causes carousel
    $causes = [
        ['image' => 'images/givezakat.png', 'title' => 'Give Zakat'],
        ['image' => 'images/givesadqa.jpg', 'title' => 'Give Sadaqah'],
        ['image' => 'images/supporton.png', 'title' => 'Support an Orphan'],
        ['image' => 'images/handpump.jpg', 'title' => 'Water Pump'],
        ['image' => 'images/givezakat.png', 'title' => 'Give Zakat'],
        ['image' => 'images/givesadqa.jpg', 'title' => 'Give Sadaqah'],
    ];

    // Impact stats
    $impact = [
        ['value' => '14000+', 'label' => 'Disaster Relief'],
        ['value' => '950,000+', 'label' => 'Medical Assistant'],
        ['value' => '1000+', 'label' => 'Students Sponsor'],
        ['value' => '2000+', 'label' => 'Water well Projects'],
        ['value' => '3000+', 'label' => 'Family Ration Program'],
        ['value' => '50,000', 'label' => 'Marriage Gifts'],
        ['value' => '100+', 'label' => 'Schools Build'],
        ['value' => '250+', 'label' => 'Homes Build'],
    ];

    // Latest News
    $news = [
        ['image' => 'images/latestnews.png', 'tag' => 'Press Release', 'title' => 'Civilians Fleeing Afghanistan Pakistan Conflict Urgently Need Aid'],
        ['image' => 'images/latestnews.png', 'tag' => 'Emergency', 'title' => 'Civilians Fleeing Afghanistan Pakistan Conflict Urgently Need Aid'],
        ['image' => 'images/latestnews.png', 'tag' => 'Blog', 'title' => 'Civilians Fleeing Afghanistan Pakistan Conflict Urgently Need Aid'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO SLIDER ===================== --}}
    <section class="relative overflow-hidden" data-carousel="hero">
        <div class="overflow-hidden">
            <div class="nf-track flex" data-track>
                @foreach ($heroSlides as $slide)
                    <div class="relative h-[240px] w-full shrink-0 sm:h-[320px] lg:h-[400px]" data-slide>
                        <img src="{{ asset($slide['image']) }}" alt="" class="h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/20 to-transparent"></div>
                        <div class="nf-container absolute inset-0 flex items-center">
                            <h1 class="ml-auto max-w-md text-right text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-5xl">
                                {!! $slide['title'] !!}
                            </h1>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Arrows --}}
        <button type="button" data-prev aria-label="Previous"
                class="absolute left-3 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full bg-white/30 text-white backdrop-blur transition hover:bg-white/50">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <button type="button" data-next aria-label="Next"
                class="absolute right-3 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full bg-white/30 text-white backdrop-blur transition hover:bg-white/50">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>

        {{-- Dots --}}
        <div class="absolute inset-x-0 bottom-4 flex justify-center gap-2" data-dots></div>
    </section>

    {{-- ===================== QUICK DONATE BAR ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-5">
            <form class="flex flex-col items-stretch gap-3 lg:flex-row lg:items-center">
                <span class="text-lg font-semibold text-white lg:mr-2">Quick Donate</span>
                <select class="nf-select h-11 flex-1 rounded-md border-0 bg-white px-3 text-sm font-medium text-navy-dark focus:ring-2 focus:ring-brand">
                    <option>One-Off-Donation</option>
                    <option>Monthly Donation</option>
                </select>
                <select class="nf-select h-11 flex-1 rounded-md border-0 bg-white px-3 text-sm font-medium text-navy-dark focus:ring-2 focus:ring-brand">
                    <option value="" disabled selected class="text-gray-400">Select a Cause</option>
                    <option>Zakat</option>
                    <option>Sadaqah</option>
                    <option>Orphan Support</option>
                    <option>Water Pump</option>
                </select>
                <input type="number" placeholder="Value"
                       class="h-11 w-full rounded-md border-0 bg-white px-3 text-sm text-navy-dark focus:ring-2 focus:ring-brand lg:w-32">
                <button type="submit" class="btn-white h-11">
                    Donate
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </form>
        </div>
    </section>

    {{-- ===================== LATEST APPEALS ===================== --}}
    <section class="py-14">
        <div class="nf-container grid gap-10 lg:grid-cols-12" data-carousel="appeals">
            {{-- Left intro --}}
            <div class="lg:col-span-4">
                <p class="mb-2 inline-block border-b-2 border-brand pb-1 text-sm font-semibold text-navy">Latest Appeals</p>
                <h2 class="text-3xl font-bold leading-tight text-brand">Changing Lives through these Initiatives</h2>
                <div class="mt-5 flex gap-3">
                    <button type="button" data-prev class="nf-arrow" aria-label="Previous">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <button type="button" data-next class="nf-arrow" aria-label="Next">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>

            {{-- Right cards (paged carousel) --}}
            <div class="lg:col-span-8">
                <div class="overflow-hidden">
                    <div class="nf-track flex" data-track>
                        @foreach ($appealPages as $page)
                            <div class="w-full shrink-0" data-slide>
                                <div class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                                    @foreach ($page as $appeal)
                                        <a href="#" class="group flex gap-4">
                                            <img src="{{ asset($appeal['image']) }}" alt="{{ $appeal['title'] }}"
                                                 class="h-16 w-20 shrink-0 rounded object-cover">
                                            <div>
                                                <h3 class="font-semibold text-navy-dark group-hover:text-brand">{{ $appeal['title'] }}</h3>
                                                <p class="mt-1 text-sm text-gray-500">{{ $appeal['text'] }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CAUSES CAROUSEL ===================== --}}
    <section class="relative overflow-hidden bg-navy py-12" data-carousel="causes">
        <div class="nf-container">
            <div class="overflow-hidden">
                <div class="nf-track flex" data-track>
                    @foreach ($causes as $cause)
                        <div class="w-full shrink-0 px-3 sm:w-1/2 lg:w-1/4" data-card>
                            <div class="overflow-hidden rounded-lg bg-white">
                                <img src="{{ asset($cause['image']) }}" alt="{{ $cause['title'] }}"
                                     class="h-44 w-full object-cover">
                                <div class="p-4 text-center">
                                    <h3 class="mb-3 font-semibold text-navy-dark">{{ $cause['title'] }}</h3>
                                    <a href="#" class="btn-brand w-full">Donate</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="button" data-prev aria-label="Previous"
                class="absolute left-1 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full text-white transition hover:text-white/60 sm:left-3">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <button type="button" data-next aria-label="Next"
                class="absolute right-1 top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full text-white transition hover:text-white/60 sm:right-3">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </section>

    {{-- ===================== OUR IMPACT ===================== --}}
    <section class="py-14">
        <div class="nf-container">
            <h2 class="mb-8 text-2xl font-bold text-brand">Our Impact</h2>
            <div class="grid grid-cols-2 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($impact as $stat)
                    <div class="flex flex-col items-center rounded-lg border border-gray-200 py-6 text-center">
                        <span class="mb-3 grid h-14 w-14 place-items-center rounded-full bg-brand text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="text-xl font-bold text-navy-dark">{{ $stat['value'] }}</span>
                        <span class="mt-1 text-sm text-gray-500">{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== LATEST NEWS ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-brand">Latest News</h2>
                <a href="#" class="text-sm font-semibold text-navy underline hover:text-brand">View all</a>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($news as $item)
                    <article class="group relative overflow-hidden rounded-lg">
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}"
                             class="h-56 w-full object-cover">
                        <span class="absolute right-0 top-3 bg-brand px-3 py-1 text-xs font-semibold text-white">{{ $item['tag'] }}</span>
                        <div class="absolute inset-x-0 bottom-0 bg-navy/85 p-4">
                            <h3 class="text-sm font-semibold leading-snug text-white">{{ $item['title'] }}</h3>
                            <a href="#" class="mt-2 inline-block text-xs font-semibold text-white underline">Read more</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== BECOME A VOLUNTEER ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <h2 class="mb-5 text-2xl font-bold text-brand">Become a Volunteer</h2>
            <div class="grid items-stretch rounded-lg bg-cream lg:grid-cols-2">
                {{-- Image with inset padding + icon tiles on the right edge --}}
                <div class="relative p-4 sm:p-6">
                    <img src="{{ asset('images/voluntear.png') }}" alt="Volunteers"
                         class="h-64 w-full rounded-md object-cover sm:h-80 lg:h-full">

                    <div class="absolute right-1 top-1/2 flex -translate-y-1/2 flex-col gap-3 sm:right-2">
                        {{-- Community Events --}}
                        <div class="flex w-16 flex-col items-center gap-1 rounded-md bg-brand px-1 py-2 text-center text-white shadow-lg">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-1.5a3.5 3.5 0 0 0-2.7-3.4M9 20H3v-1.5a3.5 3.5 0 0 1 2.7-3.4M16 11a3 3 0 1 0-2.5-1.3M8 11a3 3 0 1 0 2.5-1.3M12 14a3.5 3.5 0 0 1 3.5 3.5V20h-7v-2.5A3.5 3.5 0 0 1 12 14Z"/>
                            </svg>
                            <span class="text-[8px] font-semibold leading-tight">Community Events</span>
                        </div>
                        {{-- Mentorship --}}
                        <div class="flex w-16 flex-col items-center gap-1 rounded-md bg-brand px-1 py-2 text-center text-white shadow-lg">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 21V4a1 1 0 0 1 1-1h0M4 5h11l-2 3 2 3H4"/>
                            </svg>
                            <span class="text-[8px] font-semibold leading-tight">Mentorship</span>
                        </div>
                        {{-- Environment --}}
                        <div class="flex w-16 flex-col items-center gap-1 rounded-md bg-brand px-1 py-2 text-center text-white shadow-lg">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 21c-4-1-7-4.5-7-9 0-3 5-9 7-9s7 6 7 9c0 4.5-3 8-7 9Zm0 0v-9"/>
                            </svg>
                            <span class="text-[8px] font-semibold leading-tight">Environment</span>
                        </div>
                    </div>
                </div>

                {{-- Text --}}
                <div class="flex flex-col justify-center p-6 lg:p-10">
                    <p class="text-sm font-semibold text-gray-500">Make a Difference</p>
                    <h3 class="mt-1 max-w-xs text-2xl font-bold leading-snug text-navy-dark">Join Our Volunteer Community</h3>
                    <p class="mt-3 max-w-md text-sm leading-relaxed text-gray-600">
                        Join our community and create a great profile to make the most of our services. Join our community
                        and create a great profile to make the most of our services.
                    </p>
                    <a href="#" class="btn-navy mt-5 self-start">
                        Get Involved
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <p class="mt-3 text-xs text-gray-500">Sign up for our most-needed positions</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== SUBSCRIBE ===================== --}}
    <section class="pb-16">
        <div class="nf-container">
            <div class="rounded-lg bg-navy px-6 py-10 text-center text-white">
                <h2 class="text-2xl font-bold">Subscribe</h2>
                <p class="mt-2 text-sm text-white/80">Sign up with your email address to receive news and updates.</p>
                <form class="mx-auto mt-6 flex max-w-3xl flex-col gap-3 sm:flex-row">
                    <input type="text" placeholder="First Name" class="h-11 flex-1 rounded-md border-0 bg-white px-3 text-sm text-navy-dark placeholder:text-gray-400 focus:ring-2 focus:ring-brand">
                    <input type="text" placeholder="Last Name" class="h-11 flex-1 rounded-md border-0 bg-white px-3 text-sm text-navy-dark placeholder:text-gray-400 focus:ring-2 focus:ring-brand">
                    <input type="email" placeholder="Email" class="h-11 flex-1 rounded-md border-0 bg-white px-3 text-sm text-navy-dark placeholder:text-gray-400 focus:ring-2 focus:ring-brand">
                    <button type="submit" class="btn-brand h-11">Subscribe</button>
                </form>
                <p class="mt-4 text-xs text-white/70">We respect your privacy.</p>
            </div>
        </div>
    </section>

@endsection
