@extends('layouts.app')

@section('title', 'Kaffarah — ' . config('app.name'))

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

    $when = [
        ['title' => 'A Broken Fast', 'text' => 'Deliberately breaking a fast in Ramadan without a valid excuse.', 'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2" stroke-linecap="round"/>'],
        ['title' => 'A Broken Oath', 'text' => 'Failing to keep a solemn oath or promise made to Allah.', 'icon' => '<path d="M12 3l7 4v5c0 4.4-3 8.4-7 9-4-.6-7-4.6-7-9V7l7-4z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Feeding 60 People', 'text' => 'The expiation is to feed sixty poor people two meals for the day.', 'icon' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4zM3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];
@endphp

@section('content')

    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives2.jpg',
        'heroEyebrow' => 'Islamic Giving',
        'heroTitle' => 'Fulfil Your <span class="text-cream">Kaffarah</span>',
        'heroSubtitle' => 'Put right a broken fast or oath by feeding those in need. We distribute your Kaffarah as nourishing meals to sixty poor people on your behalf.',
        'widgetCauses' => ['Kaffarah', 'Where Most Needed'],
    ])

    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div class="nf-reveal">
                <h2 class="text-2xl font-extrabold leading-tight text-navy-dark sm:text-3xl">Fulfil Your Kaffarah, Feed Those in Need</h2>
                <p class="mt-3 text-lg font-bold italic text-brand sm:text-xl">&ldquo;The expiation is to feed sixty poor people.&rdquo; (Qur’an 58:4)</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Kaffarah is an act of expiation prescribed in Islam for certain unfulfilled obligations, including
                    specific violations related to fasting and solemn oaths. For those required to offer Kaffarah by
                    feeding the poor, Naeem Foundation provides a trusted way to fulfil this responsibility.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Your Kaffarah is used to provide nourishing meals to people and families facing hardship, ensuring that
                    your act of worship reaches those who need it most. Fulfil your obligation with care and confidence,
                    and turn an act of expiation into an opportunity to feed, support, and bring dignity to those in need.
                </p>
            </div>
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'kaffarah'])
            </div>
        </div>
    </section>

    <section class="pb-14">
        <div class="nf-container">
            <div class="nf-reveal text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Put it right</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">When Kaffarah Applies</h2>
            </div>
            <div class="mt-9 grid gap-5 sm:grid-cols-3">
                @foreach ($when as $i => $p)
                    <div class="nf-reveal flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-navy/10" data-reveal-delay="{{ $i * 60 }}">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $p['icon'] !!}</svg>
                        </span>
                        <h3 class="mt-4 text-base font-bold text-navy-dark">{{ $p['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $p['text'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="nf-reveal mx-auto mt-8 max-w-2xl rounded-2xl bg-cream p-6 text-center ring-1 ring-navy/10 sm:p-8">
                <p class="text-sm font-semibold uppercase tracking-wide text-brand">Kaffarah for one broken fast</p>
                <p class="mt-2 text-3xl font-extrabold text-navy-dark">{{ money(240, 0) }}</p>
                <p class="mt-1 text-xs text-gray-500">Feeds 60 people two meals each. Enter this amount &mdash; or a multiple &mdash; in the form above.</p>
            </div>
        </div>
    </section>

    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Restore your peace of mind</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Fulfil Your Duty With Confidence</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Give your Kaffarah today and let us feed sixty families on your behalf &mdash; putting right what was broken
                and bringing relief to those in need.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">Pay Your Kaffarah
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
