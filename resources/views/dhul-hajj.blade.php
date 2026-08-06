@extends('layouts.app')

@section('title', 'Dhul Hajj — ' . config('app.name'))

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
        'heroImage' => 'images/changinslives1.jpg',
        'heroEyebrow' => 'Islamic Giving',
        'heroTitle' => 'Support <span class="text-cream">Dhul Hajj</span> with Purpose',
        'heroSubtitle' => 'Help provide meaningful support for families and communities during the sacred season of Dhul Hajj with compassion, dignity and lasting impact.',
        'widgetCauses' => ['Dhul Hajj'],
    ])

    {{-- ===================== INTRO + VIDEO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div class="nf-reveal">
                <p class="inline-block border-b-2 border-brand pb-1 text-sm font-semibold uppercase tracking-wide text-brand">A sacred act of generosity</p>
                <h2 class="mt-3 text-3xl font-extrabold leading-tight text-navy-dark sm:text-4xl">Dhul Hajj is a time to give with sincerity</h2>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    During the blessed season of Dhul Hajj, communities face immense needs. Your support helps provide food, shelter, essential supplies and care for vulnerable families who depend on kindness and practical help.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    By giving today, you are not only meeting urgent needs but also sharing the spirit of sacrifice, compassion and remembrance that defines this sacred time.
                </p>
            </div>
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'dhul-hajj'])
            </div>
        </div>
    </section>

    {{-- ===================== PANEL HIGHLIGHT ===================== --}}
    @include('partials.give.highlight', [
        'variant' => 'panel',
        'eyebrow' => 'A season of meaning',
        'title' => 'Every donation becomes a blessing shared',
        'body' => 'When families are struggling, even the smallest act of kindness can bring stability, comfort and renewed hope. During Dhul Hajj, generosity is not only an act of giving, but also an act of worship and connection.',
        'points' => [
            'Immediate relief for families in hardship',
            'Support rooted in dignity, care and sustainability',
            'A share in the reward of this blessed season',
        ],
        'panelText' => 'Give with intention this Dhul Hajj — your sacrifice today becomes a lifeline for a family who is counting on your compassion.',
    ])

    {{-- ===================== STATS BAND (cream) ===================== --}}
    @include('partials.give.stats', [
        'variant' => 'cream',
        'eyebrow' => 'Your impact',
        'title' => 'Compassion in action',
        'stats' => [
            ['num' => '10 days', 'label' => 'of blessed giving in Dhul Hajj'],
            ['num' => '1000s', 'label' => 'of families reached each season'],
            ['num' => '4', 'label' => 'regions your gift can reach'],
            ['num' => '100%', 'label' => 'of your donation put to work'],
        ],
    ])

    {{-- ===================== DONATION TIERS ===================== --}}
    @include('partials.give.tiers', [
        'eyebrow' => 'Ways to give',
        'title' => 'Choose your Dhul Hajj gift',
        'intro' => 'Turn your generosity into meaningful relief for families during this sacred period.',
        'tiers' => [
            ['amount' => 50, 'label' => 'A Food Pack', 'note' => 'Nutritious essentials to feed a struggling family through the season.'],
            ['amount' => 120, 'label' => 'Family Relief', 'note' => 'Food, shelter support and essentials for a household in hardship.', 'featured' => true],
            ['amount' => 300, 'label' => 'Community Support', 'note' => 'Lasting help that reaches several families and strengthens a community.'],
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
