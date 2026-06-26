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
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-xl shadow-navy/5">
                    <div class="bg-gradient-to-br from-navy to-navy-dark px-6 py-5 text-center">
                        <h2 class="text-lg font-bold text-white sm:text-xl">Donate For Betterment</h2>
                        <p class="mt-1 text-xs text-white/70">100% of your donation reaches those in need.</p>
                    </div>

                    <div class="space-y-5 p-6 sm:p-7">
                        {{-- Frequency --}}
                        <div class="grid grid-cols-2 gap-3" data-choice-group>
                            <button type="button" data-choice class="nf-choice is-selected py-2.5">One-Off</button>
                            <button type="button" data-choice class="nf-choice py-2.5">Monthly</button>
                        </div>

                        {{-- Currency --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-navy-dark">Select your currency</label>
                            <div class="nf-cselect h-11 rounded-md border border-gray-300" data-cselect>
                                <button type="button" class="nf-cselect__btn" data-cselect-btn aria-haspopup="listbox" aria-expanded="false">
                                    <span data-cselect-label>GBP £</span>
                                    <svg class="nf-cselect__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <ul class="nf-cselect__menu" role="listbox" data-cselect-menu>
                                    <li class="nf-cselect__opt is-selected" role="option" data-value="GBP">GBP £</li>
                                    <li class="nf-cselect__opt" role="option" data-value="USD">USD $</li>
                                    <li class="nf-cselect__opt" role="option" data-value="EUR">EUR €</li>
                                    <li class="nf-cselect__opt" role="option" data-value="PKR">PKR ₨</li>
                                    <li class="nf-cselect__opt" role="option" data-value="CAD">CAD $</li>
                                </ul>
                                <input type="hidden" name="currency" data-cselect-input value="GBP">
                            </div>
                        </div>

                        {{-- Amounts --}}
                        <div class="grid grid-cols-4 gap-2" data-choice-group>
                            <button type="button" data-choice class="nf-choice py-2.5">£30</button>
                            <button type="button" data-choice class="nf-choice is-selected py-2.5">£50</button>
                            <button type="button" data-choice class="nf-choice py-2.5">£100</button>
                            <button type="button" data-choice class="nf-choice py-2.5">Other</button>
                        </div>

                        {{-- Cause --}}
                        <div class="nf-cselect h-11 rounded-md border border-gray-300" data-cselect>
                            <button type="button" class="nf-cselect__btn nf-cselect__btn--placeholder" data-cselect-btn aria-haspopup="listbox" aria-expanded="false">
                                <span data-cselect-label>Select a cause</span>
                                <svg class="nf-cselect__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                            <ul class="nf-cselect__menu" role="listbox" data-cselect-menu>
                                <li class="nf-cselect__opt" role="option" data-value="Fidya">Fidya</li>
                                <li class="nf-cselect__opt" role="option" data-value="Kaffarah">Kaffarah</li>
                                <li class="nf-cselect__opt" role="option" data-value="Where Most Needed">Where Most Needed</li>
                            </ul>
                            <input type="hidden" name="cause" data-cselect-input value="">
                        </div>

                        {{-- Gift Aid --}}
                        <div class="rounded-xl bg-brand p-4 text-white">
                            <p class="text-sm font-bold">Gift Aid</p>
                            <p class="mt-1 text-xs leading-relaxed text-white/85">
                                As a UK taxpayer, you can increase the impact of your donation by 25% at no extra cost.
                                Simply check the Gift Aid box when you donate.
                            </p>
                            <label class="mt-3 flex cursor-pointer items-start gap-2 text-xs leading-relaxed text-white/95">
                                <input type="checkbox" name="gift_aid" value="1" class="mt-0.5 h-4 w-4 shrink-0 rounded border-white/50 bg-white/20 text-white focus:ring-white/40">
                                Yes, I am a UK taxpayer and want Naeem Foundation to claim Gift Aid on my donation.
                            </label>
                        </div>

                        <button type="button" class="btn-brand w-full py-3">
                            Donate Now
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>
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
