@extends('layouts.app')

@section('title', 'Qurbani — ' . config('app.name'))

@php
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
            (object) ['image' => 'images/handpump.jpg', 'title' => 'Clean Water', 'description' => 'Hand pumps and wells bringing safe water to communities in need.', 'link' => '#'],
        ]);
    }
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives2.jpg',
        'heroEyebrow' => 'Qurbani',
        'heroTitle' => 'Give Your <span class="text-cream">Qurbani</span> with Purpose',
        'heroSubtitle' => 'Honour the Sunnah of sacrifice this Eid al-Adha. Your Qurbani provides fresh, quality meat to families who need it most — delivered with care and dignity.',
        'widgetCauses' => ['Qurbani'],
    ])

    {{-- ===================== INTRO + VIDEO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div class="nf-reveal">
                <p class="inline-block border-b-2 border-brand pb-1 text-sm font-semibold uppercase tracking-wide text-brand">A blessed act of sacrifice</p>
                <h2 class="mt-3 text-3xl font-extrabold leading-tight text-navy-dark sm:text-4xl">Share the spirit of Qurbani this Eid</h2>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Qurbani is a cherished act of worship that follows the example of Prophet Ibrahim (AS). By giving your
                    Qurbani through Naeem Foundation, you ensure fresh, quality meat reaches families who go without —
                    exactly when they need it most.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Every animal is sourced, sacrificed and distributed with the utmost care, so your sacred obligation is
                    fulfilled with dignity and your reward, Insha&rsquo;Allah, is complete.
                </p>
            </div>
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'qurbani'])
            </div>
        </div>
    </section>

    {{-- ===================== HOW IT WORKS (STEPS) ===================== --}}
    @include('partials.give.steps', [
        'eyebrow' => 'How it works',
        'title' => 'Your Qurbani, done right',
        'intro' => 'From your donation to a family’s plate — every step is handled on your behalf with care and integrity.',
        'steps' => [
            ['title' => 'You give your Qurbani', 'text' => 'Choose a single share, a full animal, or give in a loved one’s name.'],
            ['title' => 'We source & sacrifice', 'text' => 'Healthy animals are sacrificed according to Shariah on your behalf.'],
            ['title' => 'Fresh meat prepared', 'text' => 'The meat is cut and packed hygienically, ready for distribution.'],
            ['title' => 'Delivered to families', 'text' => 'Shared with the most vulnerable so they too can enjoy Eid.'],
        ],
    ])

    {{-- ===================== QUOTE ===================== --}}
    @include('partials.give.quote', [
        'variant' => 'cream',
        'quote' => 'For many families, Qurbani meat is the only proper meal they will eat all year — your sacrifice becomes their celebration.',
        'author' => 'Naeem Foundation Field Team',
        'role' => 'Distributing your Qurbani on the ground',
    ])

    {{-- ===================== FAQ ===================== --}}
    @include('partials.give.faq', [
        'eyebrow' => 'Good to know',
        'title' => 'Qurbani questions, answered',
        'faqs' => [
            ['q' => 'When will my Qurbani be performed?', 'a' => 'Your Qurbani is carried out during the days of Eid al-Adha (10th–12th Dhul Hijjah), as soon as your donation is received.'],
            ['q' => 'Where is the meat distributed?', 'a' => 'Fresh meat is distributed to the poorest families in the communities we serve, prioritising orphans, widows and those with no other means.'],
            ['q' => 'Can I give Qurbani for someone who has passed away?', 'a' => 'Yes. You can give a Qurbani on behalf of a loved one who has passed away, as a continuing reward for them, Insha’Allah.'],
            ['q' => 'How much is one share?', 'a' => 'A sheep or goat is one full Qurbani, while a larger animal such as a cow is shared between seven people. Prices are shown on the donation form and may vary by region.'],
        ],
    ])

    {{-- ===================== OUR PROJECTS ===================== --}}
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
            <div class="mt-10">
                @include('partials.projects-carousel')
            </div>
        </div>
    </section>

@endsection
