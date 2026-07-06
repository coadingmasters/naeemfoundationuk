@extends('layouts.app')

@section('title', 'Zakat ul Fitr (Fitrana) — ' . config('app.name'))

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

    // Why give Fitrana — reasons (icon = single-path SVG).
    $reasons = [
        ['title' => 'Fulfil Your Obligation', 'text' => 'Fitrana is a duty upon every Muslim who can afford it, ensuring that no one is left hungry on the day of Eid.', 'icon' => '<path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Provide Essential Support', 'text' => 'Your contribution helps those struggling with financial hardships, so they can celebrate Eid with their families.', 'icon' => '<path d="M20.8 6.6a5 5 0 0 0-7.1 0L12 8.3l-1.7-1.7a5 5 0 1 0-7.1 7.1L12 22l8.8-8.3a5 5 0 0 0 0-7.1z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Purify Your Fast', 'text' => 'As an act of charity, Fitrana cleanses any shortcomings in our Ramadan fasts and strengthens our faith.', 'icon' => '<path d="M12 3s6 6 6 10a6 6 0 0 1-12 0c0-4 6-10 6-10z" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/givezakat.png',
        'heroEyebrow' => 'Fitrana & Eid Gifts',
        'heroTitle' => 'A Gift of Purity and <span class="text-cream">Compassion</span> Today',
        'heroSubtitle' => 'Fitrana, also known as Zakat al-Fitr, is an obligatory charity given before Eid al-Fitr to purify your fasts and help those in need celebrate Eid with dignity and joy.',
        'widgetCauses' => ['Zakat ul Fitr', 'Eid Gifts', 'Where Most Needed'],
    ])

    {{-- ===================== WHY GIVE FITRANA ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">An obligation before Eid</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Why Give Fitrana?</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-500 sm:text-base">
                    At Naeem Foundation, we believe in the power of giving and in ensuring that everyone in our community
                    can celebrate Eid with dignity and joy.
                </p>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($reasons as $reason)
                    <div class="nf-reveal flex h-full flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-cream text-brand">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">{!! $reason['icon'] !!}</svg>
                        </span>
                        <h3 class="mt-4 font-bold text-navy-dark">{{ $reason['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $reason['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== HOW MUCH + DONATE TODAY (cream) ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="grid gap-6 lg:grid-cols-3">
                {{-- How much is Fitrana --}}
                <div class="nf-reveal flex flex-col justify-center rounded-2xl bg-navy px-6 py-8 text-center text-white sm:px-8">
                    <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">How much is Fitrana?</p>
                    <p class="mt-3 text-5xl font-extrabold text-white">£5</p>
                    <p class="mt-1 text-sm text-white/70">recommended, per person</p>
                    <p class="mt-4 text-sm leading-relaxed text-white/80">
                        Give more to extend your support to more families this Eid.
                    </p>
                    <a href="#donate" class="btn-brand mx-auto mt-6 px-7 py-2.5">
                        Give Fitrana
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>

                {{-- Donate your Fitrana today --}}
                <div class="nf-reveal rounded-2xl bg-cream px-6 py-8 sm:px-10 lg:col-span-2">
                    <h2 class="text-2xl font-bold text-navy-dark">Donate Your Fitrana Today</h2>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                        By donating your Fitrana through Naeem Foundation, you ensure that your charity reaches those who
                        need it most. We distribute Fitrana to underprivileged families, widows, orphans and individuals
                        facing extreme poverty, providing them with essential food and resources for Eid.
                    </p>
                    <ul class="mt-4 space-y-2.5 text-sm text-gray-600 sm:text-base">
                        <li class="flex gap-2"><span class="font-bold text-brand">›</span><span>Reaches underprivileged families, widows and orphans.</span></li>
                        <li class="flex gap-2"><span class="font-bold text-brand">›</span><span>Delivered before Eid so no one is left hungry on the day.</span></li>
                        <li class="flex gap-2"><span class="font-bold text-brand">›</span><span><span class="font-semibold text-navy-dark">Gift Aid:</span> UK taxpayers can add 25% at no extra cost — just tick the box.</span></li>
                    </ul>
                    <p class="mt-4 text-sm font-semibold text-navy-dark">
                        Make a difference in someone’s Eid — together, let’s make this Eid a time of happiness for all.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== EID GIFTS STORY ===================== --}}
    <section class="pb-16">
        <div class="nf-container">
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="grid lg:grid-cols-2">
                    {{-- Image --}}
                    <div class="relative min-h-[280px] lg:min-h-full">
                        <img src="{{ asset('images/supporton.png') }}" alt="A child receiving an Eid gift"
                             class="absolute inset-0 h-full w-full object-cover">
                        <span class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-brand px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-white shadow-lg">
                            <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                            Eid Gifts
                        </span>
                    </div>

                    {{-- Text --}}
                    <div class="p-6 sm:p-10">
                        <h2 class="text-2xl font-bold text-brand">Spread Joy with Naeem Foundation</h2>
                        <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                            For some families, the joy of Eid is limited by financial challenges. With your generous
                            support, Naeem Foundation brings smiles to children by providing Eid Gifts — from toys and
                            clothes to books and school supplies. Every gift is a token of love, showing them their hard
                            work and sacrifices during Ramadan are appreciated.
                        </p>

                        <figure class="mt-6 rounded-xl border-l-4 border-brand bg-cream/60 p-5">
                            <blockquote class="text-sm leading-relaxed italic text-navy-dark sm:text-base">
                                “Alhamdulillah, I am so thankful to Naeem Foundation for the beautiful Eid gifts they
                                distributed. This gesture allowed my children to experience the joy of Eid and celebrate
                                just like other children. May Allah reward them abundantly.”
                            </blockquote>
                            <figcaption class="mt-3 text-xs font-semibold text-gray-500">— Fatima, mother of three</figcaption>
                        </figure>

                        <a href="#donate" class="btn-navy mt-6 px-7 py-2.5">
                            Gift an Eid Smile
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
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
