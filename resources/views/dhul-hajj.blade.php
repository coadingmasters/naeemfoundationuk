@extends('layouts.app')

@section('title', 'Dhul Hajj — ' . config('app.name'))

@section('header-solid', 'yes')

@section('content')
    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives1.jpg',
        'heroEyebrow' => 'Islamic Giving',
        'heroTitle' => 'Support <span class="text-cream">Dhul Hajj</span> with Purpose',
        'heroSubtitle' => 'Help provide meaningful support for families and communities during the sacred season of Dhul Hajj with compassion, dignity and lasting impact.',
        'widgetCauses' => ['Dhul Hajj'],
    ])

    {{-- ===================== INTRO SECTION ===================== --}}
    <section class="py-16 sm:py-20">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div>
                <p class="inline-block border-b-2 border-brand pb-1 text-sm font-semibold uppercase tracking-wide text-brand">A sacred act of generosity</p>
                <h2 class="mt-3 text-3xl font-extrabold leading-tight text-navy-dark sm:text-4xl">Dhul Hajj is a time to give with sincerity</h2>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    During the blessed season of Dhul Hajj, communities face immense needs. Your support helps provide food, shelter, essential supplies and care for vulnerable families who depend on kindness and practical help.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    By giving today, you are not only meeting urgent needs but also sharing the spirit of sacrifice, compassion and remembrance that defines this sacred time.
                </p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-cream p-7 shadow-sm">
                <h3 class="text-xl font-bold text-navy-dark">Why this matters</h3>
                <ul class="mt-4 space-y-3 text-sm leading-relaxed text-gray-700">
                    <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-brand"></span>Provides immediate relief to families in hardship</li>
                    <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-brand"></span>Supports projects rooted in dignity, care and sustainability</li>
                    <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-brand"></span>Honours the spirit of sacrifice and generosity during Dhul Hajj</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ===================== IMPACT SECTION ===================== --}}
    <section class="pb-16 sm:pb-20">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">How your donation helps</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Compassion in action</h2>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-3">
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-navy-dark">Food support</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Ensuring vulnerable families receive nutritious meals and essential food packs during this sacred period.</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-navy-dark">Shelter & essentials</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Providing practical support that helps families remain safe, stable and cared for.</p>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-navy-dark">Long-term uplift</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Supporting lasting community projects that create dignity and opportunity beyond the season.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA SECTION ===================== --}}
    <section class="pb-16 sm:pb-20">
        <div class="nf-container">
            <div class="rounded-3xl bg-navy px-6 py-10 text-center text-white sm:px-10 lg:px-12">
                <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Give with purpose this Dhul Hajj</p>
                <h2 class="mt-3 text-3xl font-bold sm:text-4xl">Your generosity can bring relief, comfort and hope</h2>
                <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                    Choose the amount that feels right for you and help make this season a source of blessing for families in need.
                </p>
                <a href="#donate" class="btn-white mt-7 inline-flex justify-center px-7 py-3">
                    Donate Now
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </section>
@endsection
