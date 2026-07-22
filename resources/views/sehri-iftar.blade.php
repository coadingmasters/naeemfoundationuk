@extends('layouts.app')

@section('title', 'Sehri & Iftar — ' . config('app.name'))

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

    $providing = [
        ['title' => 'Meal Distribution', 'text' => 'We have set up meal distribution centers in various neighbourhoods to ensure easy access for those who require assistance. Our volunteers work tirelessly to prepare and distribute Sehri meals every night throughout Ramadan.'],
        ['title' => 'Nutritious Options', 'text' => 'We are committed to providing nutritious meals that meet the dietary needs of individuals during this special time. Our meals are carefully prepared to offer a balanced and healthy Sehri experience.'],
        ['title' => 'Community Engagement', 'text' => 'Ramadan is a time of community and unity. We invite you to join us in our efforts by volunteering at our distribution centers or spreading the word about our Sehri meal program.'],
    ];

    $arrangements = [
        ['title' => 'Provide Meals', 'text' => 'Your donations directly fund the preparation and distribution of Sehri meals, ensuring that no one in our community goes hungry during this blessed month.'],
        ['title' => 'Empowerment', 'text' => 'By supporting our Sehri program, you empower individuals and families to observe Ramadan with dignity — free from the worry of where their next meal will come from.'],
        ['title' => 'Community Support', 'text' => 'Your donations not only provide meals but also offer a sense of community and support, strengthening the bonds we share during Ramadan.'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives2.jpg',
        'heroEyebrow' => 'Sehri & Iftar',
        'heroTitle' => 'Sponsor a Meal for <span class="text-cream">Seher or Iftar</span>',
        'heroSubtitle' => 'Help a fasting family begin and break their fast with a nutritious meal this Ramadan.',
        'widgetCauses' => ['Sehri', 'Iftar', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: text --}}
            <div>
                <p class="inline-block border-b-2 border-brand pb-1 text-sm font-semibold text-brand">Sehri Support for the Needy</p>
                <h2 class="mt-4 text-2xl font-bold text-navy-dark sm:text-3xl">Naeem Foundation's Ramadan Initiative</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    At Naeem Foundation, we understand the importance of Sehri during the holy month of Ramadan. It is a
                    time for reflection, spirituality, and coming together as a community. This year, we are pleased to
                    share the arrangements we have made to support the needy during Sehri.
                </p>

                <h3 class="mt-6 text-xl font-bold text-navy-dark">Providing Nutritious Sehri Meals</h3>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Our team has been hard at work preparing nutritious and fulfilling meals for those who are less
                    fortunate. Through your generous donations, we are able to reach individuals and families who may
                    otherwise go without this essential meal during Ramadan.
                </p>
            </div>

            {{-- Right: animated video --}}
            <div>
                @include('partials.video-card', ['videoKey' => 'sehri-iftar'])
            </div>
        </div>
    </section>

    {{-- ===================== HOW IT WORKS (cream) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:px-12">
                <p class="text-sm font-semibold text-brand">How does it work?</p>

                {{-- Providing Sehri --}}
                <h3 class="mt-5 text-xl font-bold text-navy-dark">Providing Sehri</h3>
                <ul class="mt-4 space-y-4">
                    @foreach ($providing as $item)
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

                {{-- Sehri arrangements --}}
                <h3 class="mt-8 text-xl font-bold text-navy-dark">Sehri arrangements</h3>
                <ul class="mt-4 space-y-4">
                    @foreach ($arrangements as $item)
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

                {{-- Seher & Iftar --}}
                <p class="mt-8 inline-block border-b-2 border-brand pb-1 text-sm font-semibold text-brand">Your Generosity Can Change Lives</p>
                <h3 class="mt-3 text-xl font-bold text-navy-dark">Seher &amp; Iftar</h3>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    As we come together to observe this special month, we invite you to partner with us in making a
                    difference. Your donations, no matter the size, have a direct impact on the lives of those in need.
                    Together, we can ensure that everyone has the opportunity to observe Ramadan with joy and gratitude.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Thank you for considering Naeem Foundation for your Ramadan donations. May this Ramadan be a time of
                    blessings and generosity for all.
                </p>

                <div class="mt-8 border-t border-brand/15 pt-6">
                    <h4 class="text-base font-bold text-navy-dark">Donation Options</h4>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600 sm:text-base">
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Single Donations:</span> Suggested amounts (e.g. {{ region('symbol') }}50, {{ region('symbol') }}100, {{ region('symbol') }}250, {{ region('symbol') }}500).</span></li>
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Recurring Donations:</span> Set up a monthly contribution to sustain ongoing projects.</span></li>
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Currency Options:</span> PKR, GBP, USD, CAD, EUR.</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== BE THE CHANGE (CTA) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Give your time</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Be the Change They Need</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Together, we can turn this crisis around. Your donation today not only provides nutritious meals but also
                restores hope and dignity for fasting families. Join us in ensuring that everyone can observe Ramadan with
                comfort and gratitude.
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
