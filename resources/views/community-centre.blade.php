@extends('layouts.app')

@section('title', 'Community Centre — ' . config('app.name'))

@php
    // Videos are managed in the admin dashboard. Dummy clips are shown until
    // the first real video is uploaded.
    $videos = ($videos ?? collect());
    if ($videos->isEmpty()) {
        $videos = collect([
            (object) ['title' => 'Inside Our Community Centre', 'is_embed' => false, 'embed_url' => '', 'playable_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4'],
            (object) ['title' => 'Youth Programmes in Action', 'is_embed' => false, 'embed_url' => '', 'playable_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4'],
            (object) ['title' => 'Community Iftar Night', 'is_embed' => false, 'embed_url' => '', 'playable_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4'],
            (object) ['title' => 'Weekend Education Classes', 'is_embed' => false, 'embed_url' => '', 'playable_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4'],
        ]);
    }

    $featuredVideo = $videos->first();
    $galleryVideos = $videos->skip(1);

    $services = [
        ['title' => 'Daily Prayers', 'text' => 'A welcoming prayer hall open for all five daily prayers, Jumu’ah and Taraweeh.',
         'icon' => '<path d="M12 3l7 5v11a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V8l7-5z"/><path d="M9 21v-6h6v6"/>'],
        ['title' => 'Education Classes', 'text' => 'Qur’an, Arabic and Islamic studies for children and adults, taught by qualified teachers.',
         'icon' => '<path d="M22 9L12 4 2 9l10 5 10-5z"/><path d="M6 12v5c0 1.5 3 3 6 3s6-1.5 6-3v-5"/>'],
        ['title' => 'Youth Programmes', 'text' => 'Mentoring, sports and skills workshops that keep young people engaged and supported.',
         'icon' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>'],
        ['title' => 'Events & Weddings', 'text' => 'A spacious hall available to hire for nikah ceremonies, lectures and community gatherings.',
         'icon' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/>'],
        ['title' => 'Food Bank', 'text' => 'Weekly food parcels and hot meals for families and individuals facing hardship.',
         'icon' => '<path d="M6 2l1 6h10l1-6"/><path d="M5 8h14l-1 13H6L5 8z"/>'],
        ['title' => 'Counselling & Support', 'text' => 'Confidential guidance on family, wellbeing and financial matters, free of charge.',
         'icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
    ];

    $hours = [
        ['day' => 'Monday – Thursday', 'time' => '9:00 am – 9:00 pm'],
        ['day' => 'Friday', 'time' => '9:00 am – 10:00 pm'],
        ['day' => 'Saturday – Sunday', 'time' => '8:00 am – 10:00 pm'],
    ];

    $subjects = ['General enquiry', 'Hall & event booking', 'Education classes', 'Youth programmes', 'Volunteering', 'Food bank support'];
@endphp

@section('content')

    {{-- ===================== HERO + ENQUIRY FORM ===================== --}}
    <section id="enquire" class="relative overflow-hidden scroll-mt-24 bg-gradient-to-br from-navy via-navy to-navy-dark">
        <div class="pointer-events-none absolute -right-24 top-0 h-72 w-72 rounded-full bg-brand/25 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-24 -bottom-10 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>

        <div class="nf-container relative grid items-center gap-10 py-14 lg:grid-cols-2 lg:gap-14 lg:pb-20 lg:pt-32">

            {{-- Left: copy --}}
            <div class="nf-reveal text-white">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider ring-1 ring-white/20">
                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                    Community Centre
                </span>

                <h1 class="mt-4 text-3xl font-extrabold leading-[1.1] sm:text-4xl lg:text-5xl">
                    A Home for Our <span class="text-cream">Community</span>
                </h1>

                <p class="mt-4 max-w-lg text-sm leading-relaxed text-white/85 sm:text-base">
                    More than a building — a place to pray, learn, grow and belong. From daily prayers and
                    weekend classes to youth programmes and food support, the Naeem Foundation Community Centre is
                    open to everyone.
                </p>

                <div class="mt-7 grid max-w-md grid-cols-3 gap-4">
                    <div>
                        <p class="text-2xl font-extrabold sm:text-3xl">500+</p>
                        <p class="text-xs text-white/70">Weekly visitors</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold sm:text-3xl">20+</p>
                        <p class="text-xs text-white/70">Programmes</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold sm:text-3xl">7</p>
                        <p class="text-xs text-white/70">Days a week</p>
                    </div>
                </div>
            </div>

            {{-- Right: enquiry form --}}
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-xl shadow-navy/10 sm:p-6">
                <h2 class="text-lg font-bold text-navy-dark sm:text-xl">Get in touch</h2>
                <p class="mt-1 text-xs text-gray-500">Send us a message and we’ll reply within two working days.</p>

                @if (session('success'))
                    <p class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </p>
                @endif

                @if ($errors->any())
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('community-centre.enquire') }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label for="cc_name" class="mb-1.5 block text-xs font-bold text-navy">Full name <span class="text-brand">*</span></label>
                        <input id="cc_name" type="text" name="name" value="{{ old('name') }}" required class="nf-pay-input">
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="cc_email" class="mb-1.5 block text-xs font-bold text-navy">Email <span class="text-brand">*</span></label>
                            <input id="cc_email" type="email" name="email" value="{{ old('email') }}" required class="nf-pay-input">
                        </div>
                        <div>
                            <label for="cc_phone" class="mb-1.5 block text-xs font-bold text-navy">Phone</label>
                            <input id="cc_phone" type="tel" name="phone" value="{{ old('phone') }}" class="nf-pay-input">
                        </div>
                    </div>

                    <div>
                        <label for="cc_subject" class="mb-1.5 block text-xs font-bold text-navy">Subject <span class="text-brand">*</span></label>
                        <select id="cc_subject" name="subject" required class="nf-pay-input">
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject }}" @selected(old('subject') === $subject)>{{ $subject }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="cc_message" class="mb-1.5 block text-xs font-bold text-navy">Message <span class="text-brand">*</span></label>
                        <textarea id="cc_message" name="message" rows="4" required class="nf-pay-input">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="btn-brand w-full justify-center py-2.5">
                        Send Enquiry
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- ===================== WHAT WE OFFER ===================== --}}
    <section class="py-16 sm:py-20">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">What we offer</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Serving Our Community, Every Day</h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-gray-500 sm:text-base">
                    Our doors are open to people of all ages and backgrounds. Here’s what you’ll find inside.
                </p>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $i => $service)
                    <div class="nf-reveal group rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                         style="transition-delay: {{ $i * 90 }}ms">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand transition-colors duration-300 group-hover:bg-brand group-hover:text-white">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                {!! $service['icon'] !!}
                            </svg>
                        </span>
                        <h3 class="mt-4 text-lg font-bold text-navy-dark">{{ $service['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $service['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== VISIT US (cream) ===================== --}}
    <section class="pb-16 sm:pb-20">
        <div class="nf-container">
            <div class="grid gap-8 rounded-2xl bg-cream px-6 py-10 sm:px-10 lg:grid-cols-2 lg:gap-12 lg:px-12">

                <div>
                    <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">Visit Us</h2>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                        Everyone is welcome — whether you’re joining us for prayer, dropping your child at class, or
                        simply calling in for a cup of tea and a conversation.
                    </p>

                    <div class="mt-6 space-y-3 text-sm text-gray-700 sm:text-base">
                        <p class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11z" stroke-linejoin="round"/><circle cx="12" cy="10" r="2.5"/></svg>
                            2 Falcon Gate, Shire Park, Welwyn Garden City, AL7 1TW, United Kingdom
                        </p>
                        <p class="flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0 text-brand" viewBox="0 0 24 24" fill="currentColor"><path d="M6.62 10.79a15.53 15.53 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24 11.36 11.36 0 0 0 3.57.57 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11.36 11.36 0 0 0 .57 3.57 1 1 0 0 1-.24 1.02l-2.21 2.2Z"/></svg>
                            <a href="tel:+442070788118" class="hover:text-brand">+44 20 7078 8118</a>
                        </p>
                        <p class="flex items-center gap-3">
                            <svg class="h-5 w-5 shrink-0 text-brand" viewBox="0 0 24 24" fill="currentColor"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4-8 5-8-5V6l8 5 8-5v2Z"/></svg>
                            <a href="mailto:Contact@naeemfoundation.co.uk" class="hover:text-brand">Contact@naeemfoundation.co.uk</a>
                        </p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-navy-dark sm:text-xl">Opening Hours</h3>
                    <dl class="mt-4 divide-y divide-brand/15 rounded-xl bg-white px-5">
                        @foreach ($hours as $slot)
                            <div class="flex items-center justify-between py-3.5">
                                <dt class="text-sm font-semibold text-navy-dark">{{ $slot['day'] }}</dt>
                                <dd class="text-sm text-gray-600">{{ $slot['time'] }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    <a href="#enquire" class="btn-brand mt-6 w-full justify-center py-2.5">
                        Book a Visit
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== VIDEOS ===================== --}}
    <section class="pb-16 sm:pb-20">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Life at the centre</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Watch Our Community in Action</h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-gray-500 sm:text-base">
                    A glimpse of the programmes, people and moments that make our centre what it is.
                </p>
            </div>

            @if ($featuredVideo)
                <div class="mx-auto mt-10 max-w-4xl">
                    @include('partials.video-player', ['video' => $featuredVideo])
                    <p class="mt-3 text-center text-sm font-semibold text-navy-dark">{{ $featuredVideo->title }}</p>
                </div>
            @endif

            @if ($galleryVideos->isNotEmpty())
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($galleryVideos as $i => $video)
                        <div class="nf-reveal" style="transition-delay: {{ $i * 110 }}ms">
                            @include('partials.video-player', ['video' => $video])
                            <p class="mt-2.5 text-sm font-semibold text-navy-dark">{{ $video->title }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ===================== CTA ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Get involved</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Help Us Keep the Doors Open</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Every programme, class and meal is funded by your generosity. Support the centre and you support an
                entire community.
            </p>
            <a href="{{ route('donate.checkout') }}" class="btn-brand mt-7 px-7 py-3">
                Donate Now
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

@endsection
