@extends('layouts.app')

@section('title', 'Fidya & Kaffarah — ' . config('app.name'))

@php
    // "Our Projects" cards — managed in the admin dashboard. Fall back to a
    // default set if none exist yet.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food Support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives3.jpg', 'title' => 'Binoria Water Campaign', 'description' => 'Water Crisis Hit Jamia Binoria Hard — students struggle for clean water.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => 'Helping children in rural areas access quality education.', 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    $donations = [
        ['amount' => '£6.50 for two meals', 'note' => 'If you miss a single day of fasting for a valid reason.'],
        ['amount' => '£32.50 for ten meals', 'note' => 'If you miss five days of fasting.'],
        ['amount' => '£195 for sixty meals', 'note' => 'If you are unable to fast the entire month.'],
        ['amount' => '£390 for Kaffarah', 'note' => 'To feed 60 individuals as compensation for intentionally not observing a fast.'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden">
        <img src="{{ asset('images/zakatcenter.png') }}" alt="" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-dark via-brand/85 to-brand/60"></div>
        <div class="absolute inset-0 bg-navy-dark/20"></div>

        <div class="nf-container relative py-16 sm:py-20 lg:py-24">
            <div class="max-w-2xl text-white nf-reveal">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider ring-1 ring-white/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                    Fidya &amp; Kaffarah
                </span>
                <h1 class="mt-5 text-4xl font-extrabold uppercase leading-[1.05] sm:text-5xl lg:text-6xl">
                    Turn Missed<br>Fasts Into Mercy
                </h1>
                <p class="mt-4 max-w-md text-base leading-relaxed text-white/85 sm:text-lg">
                    Fulfil your obligation by feeding those who go hungry every day.
                </p>
            </div>
        </div>
    </section>

    {{-- ===================== INTRO + DONATE WIDGET ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: verse + intro + options --}}
            <div>
                <figure class="border-l-4 border-brand bg-cream/60 p-5 sm:p-6">
                    <blockquote class="text-sm italic leading-relaxed text-gray-700 sm:text-base">
                        “[Fast a] prescribed number of days. But whoever of you is ill or on a journey, then [fast] an
                        equal number of days [after Ramadan]. For those who can only fast with extreme difficulty,
                        compensation can be made by feeding a needy person… And to fast is better for you, if you only knew.”
                    </blockquote>
                    <figcaption class="mt-3 text-sm font-semibold text-brand">— Qur'an 2:184</figcaption>
                </figure>

                <h2 class="mt-7 text-2xl font-bold leading-snug text-navy-dark sm:text-3xl">
                    Fidya is a mandatory charitable act for those who cannot fast during Ramadan due to legitimate
                    health reasons.
                </h2>

                <p class="mt-6 inline-block border-b-2 border-brand pb-1 text-sm font-semibold text-brand">Donation options for Fidya</p>
                <h3 class="mt-3 text-xl font-bold text-navy-dark">Donations</h3>

                <ul class="mt-4 space-y-4">
                    @foreach ($donations as $d)
                        <li class="flex gap-3">
                            <span class="mt-1 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-brand/10 text-brand">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                            <p class="text-sm leading-relaxed text-gray-600 sm:text-base">
                                <span class="font-bold text-navy-dark">{{ $d['amount'] }}:</span> {{ $d['note'] }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Right: donate widget --}}
            <div class="lg:pl-2">
                @include('partials.donate-widget', ['widgetCauses' => ['Fidya', 'Kaffarah', 'Where Most Needed']])
            </div>
        </div>
    </section>

    {{-- ===================== FIDYA OR KAFFARAH (detail) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:px-12">
                <p class="text-sm font-semibold text-brand">Fidya or Kaffarah?</p>

                <div class="mt-6 grid gap-8 lg:grid-cols-2">
                    <div>
                        <h3 class="text-xl font-bold text-navy-dark">Fidya</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                            Recommended for those who miss fasting due to reasons like illness, pregnancy, nursing or
                            travel. It's preferable to make up the missed fasts post-Ramadan. If you're unable to do so,
                            you can contribute Fidya.
                        </p>

                        <h3 class="mt-6 text-xl font-bold text-navy-dark">Kaffarah</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                            Applies if you deliberately miss a day of fasting without a legitimate reason. The requirement
                            is to fast for 60 consecutive days or, if that's not possible, to provide meals for 60 people.
                            This applies for each day of fasting missed.
                        </p>
                    </div>

                    <div>
                        <h3 class="text-xl font-bold text-navy-dark">Fidya or Kaffarah</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600 sm:text-base">
                            The act of giving Fidya or Kaffarah through Naeem Foundation is straightforward, at £6.50 for
                            each day you couldn't fast for a legitimate reason, or £390 for each day of fasting missed
                            intentionally.
                        </p>
                        <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                            These donations ensure the provision of nutritious meals, greatly benefiting recipients by
                            offering sustenance, strength and well-being. By donating now, your contribution will be
                            allocated this Ramadan — bringing happiness, hope and relief to someone who might otherwise be
                            uncertain about their next meal.
                        </p>
                    </div>
                </div>

                <div class="mt-8 border-t border-brand/15 pt-6">
                    <h4 class="text-base font-bold text-navy-dark">Donation Options</h4>
                    <ul class="mt-3 space-y-2 text-sm text-gray-600 sm:text-base">
                        <li class="flex gap-2"><span class="font-semibold text-brand">›</span><span><span class="font-semibold text-navy-dark">Single Donations:</span> Suggested amounts (e.g. £50, £100, £250, £500).</span></li>
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
                Together, we can turn this crisis around. Your donation today not only provides clean water but also
                restores hope and fuels the dreams of future leaders. Join us in ensuring that communities remain a
                place of growth, learning and unity.
            </p>
            <a href="#" class="btn-brand mt-7 px-7 py-3">
                Support the Cause
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

            <div class="mt-10">
                @include('partials.projects-carousel')
            </div>
        </div>
    </section>

@endsection
