@extends('layouts.app')

@section('title', 'Elite Hajj 2027 — ' . config('app.name'))

@php
    // Videos are managed in the admin dashboard (Hajj Videos). Fall back to a
    // few sample online links so the gallery renders before any are added.
    $videos = ($videos ?? collect());
    if ($videos->isEmpty()) {
        $videos = collect([
            (object) ['title' => 'Hajj Reflections', 'is_embed' => false, 'embed_url' => '', 'playable_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4'],
            (object) ['title' => 'A Pilgrim’s Journey', 'is_embed' => false, 'embed_url' => '', 'playable_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4'],
            (object) ['title' => 'On the Plains of Arafat', 'is_embed' => false, 'embed_url' => '', 'playable_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4'],
            (object) ['title' => 'Returning Home', 'is_embed' => false, 'embed_url' => '', 'playable_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4'],
        ]);
    }

    // First video doubles as the featured "Hajj e Badal" clip.
    $featuredVideo = $videos->first();

    // The 8 stages of Hajj shown in the "Steps of Hajj" grid.
    $steps = [
        ['title' => 'Ihram', 'text' => 'Pilgrims enter a state of purity and intention, wearing simple garments and committing to spiritual discipline.'],
        ['title' => 'Tawaf', 'text' => 'Circling the Kaaba seven times in devotion, symbolising unity and submission to Allah.'],
        ['title' => 'Sa’i', 'text' => 'Walking between Safa and Marwah, honouring the perseverance of Hajar (AS).'],
        ['title' => 'Mina', 'text' => 'A place of reflection and preparation, where pilgrims stay and engage in worship.'],
        ['title' => 'Arafat', 'text' => 'The heart of Hajj — a day of intense duʿāʾ, repentance and forgiveness.'],
        ['title' => 'Muzdalifah', 'text' => 'A night under the open sky, gathering pebbles and remembering simplicity.'],
        ['title' => 'Rami (Stoning of Jamarat)', 'text' => 'Rejecting temptation and reaffirming obedience to Allah.'],
        ['title' => 'Qurbani & Final Tawaf', 'text' => 'Completing the sacrifice and returning to Makkah to conclude Hajj.'],
    ];

    $brochure = asset('pdf/Hajj_27_Brochure.pdf');
@endphp

@section('content')

    {{-- ===================== HERO ===================== --}}
    <section class="relative overflow-hidden bg-navy">
        <div class="grid items-stretch lg:grid-cols-2">
            {{-- Left brand panel --}}
            <div class="relative flex flex-col justify-center bg-gradient-to-br from-brand to-brand-dark px-6 py-12 sm:px-10 lg:px-14 lg:pb-20 lg:pt-32">
                <div class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rounded-full bg-white/5"></div>
                <div class="relative max-w-lg text-white">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider ring-1 ring-white/20">
                        <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                        Hajj 2027
                    </span>
                    <h1 class="mt-5 text-3xl font-extrabold leading-tight sm:text-4xl lg:text-5xl">
                        Naeem Foundation<br>Elite Hajj 2027
                    </h1>
                    <p class="mt-4 text-sm leading-relaxed text-white/85 sm:text-base">
                        Begin your sacred journey with Naeem Foundation’s trusted Hajj services. With premium hotels,
                        seamless transport, nourishing meals and spiritual guidance from respected scholars, we provide
                        comfort, care and devotion at every step — so your heart can remain focused on worship.
                    </p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="#register" class="btn-white px-7 py-3">
                            Register Now
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        <a href="{{ $brochure }}" target="_blank" rel="noopener"
                           class="inline-flex items-center justify-center gap-2 rounded-md border border-white/40 px-7 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                            View Brochure
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right image --}}
            <div class="relative min-h-[280px] lg:min-h-full">
                <img src="{{ asset('images/homepagehero.png') }}" alt="Pilgrims performing Hajj"
                     class="absolute inset-0 h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-navy/40 to-transparent lg:bg-gradient-to-l lg:from-transparent lg:to-brand/30"></div>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div class="border-t border-white/10 bg-navy-dark/60">
            <div class="nf-container py-3">
                <nav class="flex items-center gap-2 text-xs text-white/60">
                    <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                    <span>›</span>
                    <span class="text-white">Elite Hajj 2027</span>
                </nav>
            </div>
        </div>
    </section>

    {{-- ===================== HAJJ JOURNEY INTRO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container max-w-3xl">
            <h2 class="text-3xl font-bold text-navy-dark sm:text-4xl">Hajj Journey</h2>
            <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                Hajj is not just travel; it is a once-in-a-lifetime calling. It is a journey that strips away status,
                comfort and routine, bringing every pilgrim before Allah as equals. From the moment a pilgrim enters
                ihram, every step becomes an act of worship, patience, humility and devotion combined.
            </p>
            <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                With Naeem Foundation’s Elite Hajj services, pilgrims are supported throughout this sacred journey with
                care, organisation and guidance — allowing hearts to remain focused on worship while we take care of the
                rest.
            </p>
        </div>
    </section>

    {{-- ===================== WHY HAJJ IS IMPORTANT ===================== --}}
    <section class="pb-14">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div>
                <p class="inline-block border-b-2 border-brand pb-1 text-sm font-semibold text-brand">Why Hajj Is Important</p>
                <h3 class="mt-3 text-2xl font-bold text-navy-dark sm:text-3xl">A Pillar That Completes Faith</h3>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Hajj is one of the five pillars of Islam and an obligation upon every Muslim who has the physical and
                    financial ability to perform it at least once in their lifetime. It is an act of total submission,
                    reminding us of Prophet Ibrahim’s obedience, the Sunnah of Prophet Muhammad ﷺ, and the unity of the
                    Ummah.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Hajj purifies the soul, erases past sins and renews a believer’s relationship with Allah. It is a
                    moment where worldly differences disappear, and millions stand together in worship — dressed alike,
                    praying alike, humbled alike.
                </p>
            </div>
            <div class="relative">
                <img src="{{ asset('images/zakathero.png') }}" alt="The Kaaba in Makkah"
                     class="h-72 w-full rounded-2xl object-cover shadow-lg sm:h-80 lg:h-[420px]">
            </div>
        </div>
    </section>

    {{-- ===================== WHAT HAPPENS DURING HAJJ ===================== --}}
    <section class="pb-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div class="relative order-2 lg:order-1">
                <img src="{{ asset('images/givezakat.png') }}" alt="Pilgrims during Hajj"
                     class="h-72 w-full rounded-2xl object-cover shadow-lg sm:h-80 lg:h-[420px]">
            </div>
            <div class="order-1 lg:order-2">
                <p class="inline-block border-b-2 border-brand pb-1 text-sm font-semibold text-brand">What Happens During Hajj</p>
                <h3 class="mt-3 text-2xl font-bold text-navy-dark sm:text-3xl">Days of Worship & Transformation</h3>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    During Hajj, pilgrims follow a sacred timeline set by Allah and His Messenger ﷺ. These days are filled
                    with prayer, remembrance, sacrifice, patience and spiritual discipline. Pilgrims move between the holy
                    sites of Makkah, Mina, Arafat and Muzdalifah — each carrying deep meaning rooted in Islamic history.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Every ritual performed during Hajj is a reminder of faith, sacrifice and accountability — shaping the
                    pilgrim long after they return home.
                </p>
            </div>
        </div>
    </section>

    {{-- ===================== STEPS OF HAJJ ===================== --}}
    <section class="bg-cream py-16 sm:py-20">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">A sacred path followed with intention and guidance</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Steps of Hajj</h2>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($steps as $i => $step)
                    <div class="nf-reveal flex h-full flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <span class="text-2xl font-extrabold text-brand/25">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="mt-1 font-bold text-navy-dark">{{ $step['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $step['text'] }}</p>
                    </div>
                @endforeach
            </div>

            <p class="mx-auto mt-10 max-w-2xl text-center text-sm leading-relaxed text-gray-500">
                With experienced scholars and on-ground support, Naeem Foundation ensures pilgrims understand not just
                what to do, but why they do it.
            </p>
        </div>
    </section>

    {{-- ===================== HAJJ E BADAL ===================== --}}
    <section class="py-16 sm:py-20">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Sponsor a sacred journey</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Hajj e Badal</h2>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Naeem Foundation offers Hajj-e-Badal as a meaningful opportunity for Hajj to be performed on behalf of
                    those who are unable to perform it themselves due to age, illness or physical limitations. Through
                    your contribution, Hajj is carried out with care, sincerity and proper guidance on behalf of a
                    deserving individual — honouring their lifelong intention and devotion.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    This sacred act allows anyone to fulfil another person’s uncompleted obligation, earning compassion
                    into worship and ensuring a reward that continues beyond a single journey.
                </p>
                <div class="mt-6 rounded-xl border border-brand/15 bg-cream/60 p-4 text-sm text-gray-700">
                    <p>For Hajj-e-Badal, contact us:</p>
                    <p class="mt-1"><span class="font-semibold text-navy-dark">Email:</span> contact@naeemfoundation.co.uk</p>
                    <p><span class="font-semibold text-navy-dark">Phone:</span> +44 2070788118 · +44 7960185682</p>
                </div>
            </div>
            <div>
                @include('partials.hajj-video', ['video' => $featuredVideo])
            </div>
        </div>
    </section>

    {{-- ===================== REGISTRATION FORM + BROCHURE ===================== --}}
    <section id="register" class="scroll-mt-24 bg-navy py-16 sm:py-20">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Hajj programs</p>
                <h2 class="mt-2 text-3xl font-bold text-white sm:text-4xl">Registration Form</h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-white/70">
                    Register your interest for Elite Hajj 2027 and our team will guide you through packages, documents
                    and next steps.
                </p>
            </div>

            @if (session('success'))
                <div class="mx-auto mt-8 max-w-4xl rounded-xl border border-green-400/30 bg-green-500/15 px-5 py-4 text-sm font-medium text-green-100">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mt-10 grid gap-8 lg:grid-cols-2">
                {{-- Form --}}
                <div class="rounded-2xl bg-white p-6 shadow-xl sm:p-8">
                    <form method="POST" action="{{ route('hajj.register') }}" class="space-y-5">
                        @csrf

                        @if ($errors->any())
                            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                                Please check the highlighted fields and try again.
                            </div>
                        @endif

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="first_name" class="mb-1.5 block text-sm font-semibold text-navy-dark">Given name <span class="text-red-500">*</span></label>
                                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required placeholder="Name here"
                                       class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                            </div>
                            <div>
                                <label for="last_name" class="mb-1.5 block text-sm font-semibold text-navy-dark">Surname <span class="text-red-500">*</span></label>
                                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required placeholder="Name here"
                                       class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                            </div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="email" class="mb-1.5 block text-sm font-semibold text-navy-dark">Email <span class="text-red-500">*</span></label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="Email here"
                                       class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                            </div>
                            <div>
                                <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Do you have a valid Pakistani passport?</span>
                                <div class="flex h-11 items-center gap-6">
                                    <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-600">
                                        <input type="radio" name="has_pakistani_passport" value="1" {{ old('has_pakistani_passport') === '1' ? 'checked' : '' }} class="h-4 w-4 border-gray-300 text-brand focus:ring-brand">
                                        Yes
                                    </label>
                                    <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-600">
                                        <input type="radio" name="has_pakistani_passport" value="0" {{ old('has_pakistani_passport', '0') === '0' ? 'checked' : '' }} class="h-4 w-4 border-gray-300 text-brand focus:ring-brand">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="mb-1.5 block text-sm font-semibold text-navy-dark">Contact number with country code <span class="text-red-500">*</span></label>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required placeholder="+44 …"
                                   class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                        </div>

                        <label class="flex cursor-pointer items-start gap-2.5 text-sm text-gray-600">
                            <input type="checkbox" name="consent" value="1" required class="mt-0.5 h-4 w-4 shrink-0 rounded border-gray-300 text-brand focus:ring-brand">
                            <span>I am signing up for information on Hajj 2027 and consent to being contacted by Naeem Foundation for latest updates.</span>
                        </label>

                        <button type="submit" class="btn-brand w-full py-3">
                            Submit
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </form>
                </div>

                {{-- Brochure preview + download --}}
                <div class="rounded-2xl bg-white p-6 shadow-xl sm:p-8">
                    <h3 class="text-lg font-bold text-navy-dark">Elite Hajj 2027 Brochure</h3>
                    <p class="mt-1 text-sm text-gray-500">Preview the full package details, inclusions and pricing.</p>

                    <div class="mt-5 overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
                        <object data="{{ $brochure }}#toolbar=0&view=FitH" type="application/pdf"
                                class="h-[420px] w-full">
                            <div class="flex h-[420px] flex-col items-center justify-center gap-3 p-6 text-center">
                                <span class="grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" stroke-linejoin="round"/><path d="M14 3v5h5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </span>
                                <p class="text-sm text-gray-500">PDF preview isn’t supported on this device.</p>
                                <a href="{{ $brochure }}" target="_blank" rel="noopener" class="btn-navy">Open the brochure</a>
                            </div>
                        </object>
                    </div>

                    <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ $brochure }}" download class="btn-brand flex-1 py-3">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Download PDF
                        </a>
                        <a href="{{ $brochure }}" target="_blank" rel="noopener" class="btn-navy flex-1 py-3">
                            Open in New Tab
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== RECENT HAJJ REVIEWS (VIDEO GALLERY) ===================== --}}
    <section class="py-16 sm:py-20">
        <div class="nf-container">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Video gallery</p>
                <h2 class="mt-2 text-3xl font-bold text-navy-dark sm:text-4xl">Recent Hajj Reviews</h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-gray-500 sm:text-base">
                    Hear directly from pilgrims who travelled with Naeem Foundation about their experience of a lifetime.
                </p>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($videos as $video)
                    <div>
                        @include('partials.hajj-video', ['video' => $video])
                        <p class="mt-3 text-sm font-semibold text-navy-dark">{{ $video->title }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
