@extends('layouts.app')

@section('title', 'Dhul Hajj — ' . config('app.name'))

{{-- Image hero (donate-hero) below, so the header stays a transparent overlay
     that fades to solid on scroll — matching every other Giving page. --}}

@php
    // "Our Projects" carousel — real admin projects when present, else these defaults.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
            (object) ['image' => 'images/handpump.jpg', 'title' => 'Clean Water', 'description' => 'Hand pumps and wells bringing safe water to communities in need.', 'link' => '#'],
        ]);
    }

    // How a Dhul Hajj gift is put to work.
    $impact = [
        ['title' => 'Food support', 'text' => 'Nutritious meals and essential food packs for vulnerable families during this sacred period.', 'icon' => '<path d="M4 12h4l2 5 4-10 2 5h4" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Shelter & essentials', 'text' => 'Practical support that helps families stay safe, stable and cared for.', 'icon' => '<path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Long-term uplift', 'text' => 'Lasting community projects that create dignity and opportunity beyond the season.', 'icon' => '<path d="M4 19V5a2 2 0 0 1 2-2h9l5 5v11M9 8h4M9 12h6" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Sacred giving', 'text' => 'Honouring the spirit of sacrifice and generosity that defines Dhul Hajj.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];
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
            {{-- Left: text --}}
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
            {{-- Right: animated video --}}
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'dhul-hajj'])
            </div>
        </div>
    </section>

    {{-- ===================== IMPACT ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="nf-reveal text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">How your donation helps</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">Compassion in Action</h2>
            </div>

            <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($impact as $i => $p)
                    <div class="nf-reveal flex flex-col rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-navy/10"
                         data-reveal-delay="{{ $i * 60 }}">
                        <span class="grid h-12 w-12 place-items-center rounded-xl bg-brand/10 text-brand">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $p['icon'] !!}</svg>
                        </span>
                        <h3 class="mt-4 text-base font-bold text-navy-dark">{{ $p['title'] }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $p['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== STORY ===================== --}}
    <section class="pb-14">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:gap-14">
            {{-- Left: story --}}
            <div class="nf-reveal">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">A season of meaning</p>
                <h3 class="mt-3 text-2xl font-bold text-navy-dark sm:text-3xl">Every donation becomes a blessing shared</h3>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    When families are struggling, even the smallest act of kindness can bring stability, comfort and renewed hope. Our work ensures that your support reaches those who need it most with dignity and care.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    During Dhul Hajj, this message is even more powerful — a reminder that generosity is not only an act of giving, but also an act of worship and connection.
                </p>
            </div>
            {{-- Right: "what your support provides" card --}}
            <div class="nf-reveal lg:sticky lg:top-28" data-reveal-delay="120">
                <div class="rounded-3xl bg-cream p-8 shadow-sm ring-1 ring-navy/10">
                    <h3 class="text-xl font-bold text-navy-dark">What your support can provide</h3>
                    <ul class="mt-4 space-y-3 text-sm text-gray-700">
                        <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-brand"></span>Daily meals and food essentials</li>
                        <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-brand"></span>Safe shelter and practical care</li>
                        <li class="flex gap-3"><span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-brand"></span>Long-term support for vulnerable households</li>
                    </ul>
                    <a href="#donate" class="btn-brand mt-6 px-6 py-2.5">
                        Give this Dhul Hajj
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== WHY THIS CAUSE MATTERS ===================== --}}
    <section class="pb-14">
        <div class="nf-container nf-reveal rounded-3xl border border-gray-100 bg-white p-8 shadow-sm sm:p-10">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Why this cause matters</p>
                <h3 class="mt-3 text-2xl font-bold text-navy-dark sm:text-3xl">A giving opportunity that carries lasting value</h3>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    This page is built for those who want to turn their generosity into meaningful relief for families in need. It is a simple and direct way to support the most vulnerable during a period of deep spiritual significance.
                </p>
            </div>
            <div class="mt-8 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl bg-cream/70 p-5">
                    <h4 class="font-semibold text-navy-dark">Immediate impact</h4>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">You can help meet urgent needs right away with practical support.</p>
                </div>
                <div class="rounded-2xl bg-cream/70 p-5">
                    <h4 class="font-semibold text-navy-dark">Sustainable care</h4>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Your contribution supports ongoing work that protects dignity and stability.</p>
                </div>
                <div class="rounded-2xl bg-cream/70 p-5">
                    <h4 class="font-semibold text-navy-dark">Meaningful giving</h4>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">A chance to give with intention, compassion and purpose during Dhul Hajj.</p>
                </div>
                <div class="rounded-2xl bg-cream/70 p-5">
                    <h4 class="font-semibold text-navy-dark">Community connection</h4>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600">Your support strengthens the wider community and nourishes hope.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA (navy) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Give with purpose this Dhul Hajj</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Your generosity can bring relief, comfort and hope</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Choose the amount that feels right for you and help make this season a source of blessing for families in need.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                Donate Now
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
