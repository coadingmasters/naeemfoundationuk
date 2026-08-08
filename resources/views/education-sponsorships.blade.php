@extends('layouts.app')

@section('title', 'Education Sponsorships — ' . config('app.name'))

@php
    // "Our Projects" carousel — real admin projects when present, else these defaults.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives3.jpg', 'title' => 'Binoria Water Campaign', 'description' => 'Water crisis hit Jamia Binoria hard — students struggle for clean water.', 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    // Monthly sponsorship levels (currency follows the visitor's region).
    // Course lengths confirmed by Naeem Foundation.
    $levels = [
        ['amount' => 53, 'label' => 'Hafiz / Hafiza', 'duration' => '2.5-year course', 'note' => 'Sponsor a student memorising the Holy Qur’an.'],
        ['amount' => 63, 'label' => 'Alim / Alima', 'duration' => '8-year course', 'note' => 'Sponsor an Islamic scholar in training.'],
        ['amount' => 72, 'label' => 'Mufti / Muftia', 'duration' => '2-year specialisation', 'note' => 'Sponsor advanced Islamic jurisprudence study.'],
    ];

    $focus = [
        ['title' => 'Comprehensive Curriculum', 'text' => 'A balanced blend of secular and religious knowledge.'],
        ['title' => 'Scholarships', 'text' => 'Supporting students facing financial challenges.'],
        ['title' => 'Islamic Values', 'text' => 'Instilling compassion, empathy and integrity.'],
        ['title' => 'Resources & Support', 'text' => 'Providing abundant materials and training for learning and growth.'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/changinslives1.jpg',
        'heroEyebrow' => 'Projects',
        'heroTitle' => "Empowering Tomorrow's <span class=\"text-cream\">Leaders</span> Today",
        'heroSubtitle' => 'We believe education is the key to unlocking a world of possibilities — giving every deserving student access to quality learning, whatever their circumstances.',
        'panel' => [
            'panelTitle' => 'Support Education',
            'causes' => ['Alim', 'Hafiz', 'Mufti'],
            'causeStyle' => 'buttons',
            'oneOffAmounts' => [30, 50, 100],
            'monthlyDefault' => 53,
            'yearlyDefault' => 636,
            // Sponsorship is an ongoing commitment, so the panel opens on Monthly.
            'defaultFreq' => 'monthly',
            // Which giving type the sponsorship is paid from. Sadaqah leads: it is
            // unrestricted, whereas Zakat has strict eligibility rules.
            'fundOptions' => ['Sadaqah', 'Zakat', 'Lillah'],
        ],
    ])

    {{-- ===================== INTRO + VIDEO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            {{-- Left: text --}}
            <div class="nf-reveal">
                <p class="text-lg font-bold italic text-brand sm:text-xl">&ldquo;Education is the bridge to a better tomorrow.&rdquo;</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Education is more than just learning; it is the key that opens doors to a brighter future. For
                    countless children, education can break the chains of poverty and give them the tools to build a
                    better life.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    At Naeem Foundation, we believe every child &mdash; regardless of background &mdash; deserves the
                    chance to dream and grow through quality education, and to become the next generation of leaders,
                    thinkers and changemakers who will shape a brighter tomorrow.
                </p>
            </div>
            {{-- Right: animated video --}}
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'education-sponsorships'])
            </div>
        </div>
    </section>

    {{-- ===================== SUPPORT EDUCATION ===================== --}}
    <section class="pb-14">
        <div class="nf-container grid items-start gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: sponsorship levels + focus --}}
            <div class="nf-reveal">
                <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">Support Education</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Sponsor a student at Naeem Foundation and provide a balanced education that nurtures intellect, spirit
                    and character. Choose a monthly sponsorship level:
                </p>

                <div class="mt-5 space-y-3">
                    @foreach ($levels as $i => $l)
                        <div class="nf-reveal group flex items-center gap-4 rounded-xl border border-brand/15 bg-cream/50 p-4 transition-all duration-300 hover:-translate-y-0.5 hover:border-brand/40 hover:shadow-md"
                             data-reveal-delay="{{ $i * 90 }}">
                            <span class="grid h-14 w-14 shrink-0 place-items-center rounded-full bg-brand text-sm font-extrabold text-white transition-transform duration-300 group-hover:scale-105">{{ money($l['amount'], 0) }}</span>
                            <div class="min-w-0">
                                <p class="font-bold text-navy-dark">{{ $l['label'] }} <span class="text-xs font-medium text-gray-400">/ month</span></p>
                                {{-- How long the course runs, so a sponsor knows what
                                     they are committing to before they choose. --}}
                                <p class="mt-1 inline-flex items-center gap-1.5 rounded-full bg-brand/10 px-2.5 py-0.5 text-[11px] font-bold text-brand">
                                    <svg class="h-3 w-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    {{ $l['duration'] }}
                                </p>
                                <p class="mt-1.5 text-xs leading-relaxed text-gray-500">{{ $l['note'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h3 class="mt-8 text-xl font-bold text-navy-dark sm:text-2xl">Our Focus</h3>
                <ul class="mt-4 space-y-3">
                    @foreach ($focus as $f)
                        <li class="flex gap-3">
                            <span class="mt-1 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-brand/10 text-brand">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                            <p class="text-sm leading-relaxed text-gray-600 sm:text-base"><span class="font-bold text-navy-dark">{{ $f['title'] }}:</span> {{ $f['text'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Right: image + "join us" card --}}
            <div class="nf-reveal lg:sticky lg:top-28" data-reveal-delay="120">
                <div class="overflow-hidden rounded-2xl bg-cream shadow-sm ring-1 ring-navy/10">
                    <div class="relative h-64 overflow-hidden sm:h-80">
                        <img src="{{ asset('images/changinslives3.jpg') }}" alt="Students learning" class="h-full w-full object-cover">
                        <span class="absolute inset-0 bg-gradient-to-t from-navy-dark/40 to-transparent"></span>
                    </div>
                    <div class="p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-navy-dark">Join Us in Making a Difference</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            Invest in the future of our children by supporting our educational initiatives. Your
                            contribution helps us empower young minds and create a more equitable world.
                        </p>
                        <a href="#donate" class="btn-brand mt-5 px-6 py-2.5">
                            Sponsor a Student
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== ORPHANS CAROUSEL ===================== --}}
    {{-- No top padding: the section above already closes with pb-14. --}}
    <section class="pb-14 sm:pb-16">
        <div class="nf-container">
            <div class="nf-reveal mb-8 text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Sponsor a child</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">Children Waiting for a Sponsor</h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-gray-500 sm:text-base">
                    Every child here is waiting for someone to fund their education. Tap any face to read their
                    story and sponsor them directly.
                </p>
            </div>

            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.orphans-carousel')
            </div>

            <div class="nf-reveal mt-9 text-center" data-reveal-delay="200">
                <a href="{{ route('orphans-sponsorships') }}" class="btn-navy group px-7 py-3">
                    See all children
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== CTA (navy) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Change a life</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Be the Change They Need</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Every child deserves the chance to learn, grow and lead. Your sponsorship today doesn&rsquo;t just fund an
                education &mdash; it restores hope and fuels the dreams of a future leader. Join us in building a
                generation of confident, compassionate changemakers.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                Sponsor a Student
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </section>

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
