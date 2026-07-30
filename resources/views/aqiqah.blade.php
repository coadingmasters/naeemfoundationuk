@extends('layouts.app')

@section('title', 'Aqiqah — ' . config('app.name'))

@php
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/handpump.jpg', 'title' => 'Clean Water', 'description' => 'Hand pumps and wells bringing safe water to communities in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    // Aqiqah shares (one sheep/goat per share). Amounts follow the region currency.
    $options = [
        ['amount' => 160, 'label' => 'Aqiqah for a Boy', 'note' => 'Two sheep — the recommended Sunnah for a baby boy.'],
        ['amount' => 80, 'label' => 'Aqiqah for a Girl', 'note' => 'One sheep — the recommended Sunnah for a baby girl.'],
        ['amount' => 80, 'label' => 'One Share', 'note' => 'A single sheep or goat, sacrificed and distributed on your behalf.'],
    ];
    $steps = [
        ['title' => 'You give', 'text' => 'Choose an Aqiqah for a boy, a girl, or a single share.'],
        ['title' => 'We sacrifice', 'text' => 'A healthy animal is sacrificed on your behalf, following the Sunnah.'],
        ['title' => 'Families are fed', 'text' => 'The fresh meat is distributed to poor and needy families.'],
    ];
@endphp

@section('content')

    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives2.jpg',
        'heroEyebrow' => 'Islamic Giving',
        'heroTitle' => 'Celebrate New Life with <span class="text-cream">Aqiqah</span>',
        'heroSubtitle' => 'Give thanks for the gift of a child by fulfilling the Sunnah of Aqiqah — and share the blessing with families in need.',
        'widgetCauses' => ['Aqiqah', 'Where Most Needed'],
    ])

    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div class="nf-reveal">
                <p class="text-lg font-bold italic text-brand sm:text-xl">&ldquo;Every child is pledged for its Aqiqah.&rdquo; (Abu Dawud)</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Aqiqah is the beautiful Sunnah of sacrificing an animal in gratitude for the birth of a child &mdash;
                    two sheep for a boy and one for a girl. It is a celebration of new life and a prayer of protection
                    over the newborn.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    We carry out your Aqiqah on your behalf and distribute the fresh, wholesome meat to poor families
                    &mdash; turning your joy into a meal for someone who has little.
                </p>
            </div>
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'aqiqah'])
            </div>
        </div>
    </section>

    <section class="pb-14">
        <div class="nf-container grid items-start gap-10 lg:grid-cols-2 lg:gap-14">
            {{-- Left: options --}}
            <div class="nf-reveal">
                <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">Choose Your Aqiqah</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Select the option that&rsquo;s right for your family and we&rsquo;ll take care of the rest:
                </p>
                <div class="mt-5 space-y-3">
                    @foreach ($options as $l)
                        <div class="flex items-center gap-4 rounded-xl border border-brand/15 bg-cream/50 p-4 transition-colors hover:border-brand/40">
                            <span class="grid h-14 w-14 shrink-0 place-items-center rounded-full bg-brand text-sm font-extrabold text-white">{{ money($l['amount'], 0) }}</span>
                            <div>
                                <p class="font-bold text-navy-dark">{{ $l['label'] }}</p>
                                <p class="text-xs leading-relaxed text-gray-500">{{ $l['note'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="mt-5 text-xs leading-relaxed text-gray-400">Enter your chosen amount in the form above to complete your Aqiqah.</p>
            </div>

            {{-- Right: how it works --}}
            <div class="nf-reveal lg:sticky lg:top-28" data-reveal-delay="120">
                <div class="rounded-2xl bg-cream p-6 shadow-sm ring-1 ring-navy/10 sm:p-8">
                    <h3 class="text-xl font-bold text-navy-dark">How It Works</h3>
                    <ol class="mt-5 space-y-5">
                        @foreach ($steps as $i => $s)
                            <li class="flex gap-4">
                                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand text-sm font-extrabold text-white">{{ $i + 1 }}</span>
                                <div>
                                    <p class="font-bold text-navy-dark">{{ $s['title'] }}</p>
                                    <p class="mt-0.5 text-sm leading-relaxed text-gray-600">{{ $s['text'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">A blessing shared</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Turn Your Joy Into a Meal</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Fulfil this beautiful Sunnah with confidence &mdash; we handle everything, and your Aqiqah becomes fresh
                food for families who rarely taste meat.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">Give Your Aqiqah
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        </div>
    </section>

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
