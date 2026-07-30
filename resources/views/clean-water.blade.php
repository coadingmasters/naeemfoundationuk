@extends('layouts.app')

@section('title', 'Clean Water — ' . config('app.name'))

@php
    // "Our Projects" carousel — real admin projects when present, else these defaults.
    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/handpump.jpg', 'title' => 'Clean Water', 'description' => 'Hand pumps, wells and filtration bringing safe water to communities in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Healthcare', 'description' => 'Free medical care and medicine for remote communities.', 'link' => '#'],
        ]);
    }

    // One-off water solutions the donor can fund (currency follows the region).
    $solutions = [
        ['amount' => 150, 'label' => 'A Hand Pump', 'note' => 'A dedicated hand pump giving one family a lasting, safe water source.'],
        ['amount' => 350, 'label' => 'A Family Well', 'note' => 'A shallow well serving several families in a small community.'],
        ['amount' => 1500, 'label' => 'A Community Well', 'note' => 'A deep water well that can serve an entire village for years.'],
    ];

    // What clean water changes.
    $impact = [
        ['title' => 'Better Health', 'text' => 'Safe water prevents cholera, typhoid and diarrhoeal disease that claim young lives.', 'icon' => '<path d="M4 12h4l2 5 4-10 2 5h4" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'More Learning', 'text' => 'Children attend school instead of walking hours each day to fetch water.', 'icon' => '<path d="M4 19V5a2 2 0 0 1 2-2h9l5 5v11M9 8h4M9 12h6" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Stronger Livelihoods', 'text' => 'Families can grow crops, keep livestock and build small businesses.', 'icon' => '<path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Restored Dignity', 'text' => 'Women and girls are freed from the daily burden and danger of the long walk.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];
@endphp

@section('content')

    {{-- ===================== HERO + DONATE ===================== --}}
    @include('partials.donate-hero', [
        'heroImage' => 'images/handpump.jpg',
        'heroEyebrow' => 'Projects',
        'heroTitle' => 'Clean Water, <span class="text-cream">Changed</span> Lives',
        'heroSubtitle' => 'Millions still live without safe water to drink. Your gift funds hand pumps, wells and filtration that bring clean, life-saving water to communities in need.',
        'widgetCauses' => ['Clean Water', 'Water Pump', 'Where Most Needed'],
    ])

    {{-- ===================== INTRO + VIDEO ===================== --}}
    <section class="py-14 sm:py-16">
        <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
            {{-- Left: text --}}
            <div class="nf-reveal">
                <p class="text-lg font-bold italic text-brand sm:text-xl">&ldquo;Water is life &mdash; and every drop of it is a lifeline.&rdquo;</p>
                <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">
                    For countless families, clean water is still a daily struggle. Women and children walk miles under
                    the sun to reach a source that is often unsafe, and preventable waterborne disease continues to take
                    young lives.
                </p>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    At Naeem Foundation, we believe access to clean water is a basic human right &mdash; so we install
                    hand pumps, dig wells and set up filtration to give communities a safe, sustainable source they can
                    rely on for years to come.
                </p>
            </div>
            {{-- Right: animated video --}}
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'clean-water'])
            </div>
        </div>
    </section>

    {{-- ===================== IMPACT ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="nf-reveal text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Why it matters</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">What Clean Water Brings</h2>
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

    {{-- ===================== WAYS TO GIVE ===================== --}}
    <section class="pb-14">
        <div class="nf-container grid items-start gap-10 lg:grid-cols-2 lg:gap-14">

            {{-- Left: water solutions --}}
            <div class="nf-reveal">
                <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">Fund a Water Source</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    A one-off gift can leave a lasting legacy of clean water &mdash; a Sadaqah Jariyah that keeps giving
                    long after your donation. Choose the source you&rsquo;d like to fund:
                </p>

                <div class="mt-5 space-y-3">
                    @foreach ($solutions as $l)
                        <div class="flex items-center gap-4 rounded-xl border border-brand/15 bg-cream/50 p-4 transition-colors hover:border-brand/40">
                            <span class="grid h-14 w-14 shrink-0 place-items-center rounded-full bg-brand text-sm font-extrabold text-white">{{ money($l['amount'], 0) }}</span>
                            <div>
                                <p class="font-bold text-navy-dark">{{ $l['label'] }}</p>
                                <p class="text-xs leading-relaxed text-gray-500">{{ $l['note'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="mt-5 text-xs leading-relaxed text-gray-400">
                    Every well and pump is built to last and can carry your name or the name of a loved one &mdash; a gift
                    of clean water in their memory. Give any amount using the form above.
                </p>
            </div>

            {{-- Right: image + "join us" card --}}
            <div class="nf-reveal lg:sticky lg:top-28" data-reveal-delay="120">
                <div class="overflow-hidden rounded-2xl bg-cream shadow-sm ring-1 ring-navy/10">
                    <div class="relative h-64 overflow-hidden sm:h-80">
                        <img src="{{ asset('images/handpump.jpg') }}" alt="A newly installed hand pump" class="h-full w-full object-cover">
                        <span class="absolute inset-0 bg-gradient-to-t from-navy-dark/40 to-transparent"></span>
                    </div>
                    <div class="p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-navy-dark">Join Us in Making a Difference</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            Clean water transforms everything &mdash; health, education and hope. When you give, you don&rsquo;t
                            just quench a thirst; you change the future of an entire community.
                        </p>
                        <a href="#donate" class="btn-brand mt-5 px-6 py-2.5">
                            Give Clean Water
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CTA (navy) ===================== --}}
    <section class="bg-navy">
        <div class="nf-container py-14 text-center">
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">A gift that keeps giving</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">Turn a Long Walk Into a Short One</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                No one should risk their health or safety for something as basic as a drink of water. Your donation today
                provides a clean, reliable source that will serve families for years &mdash; and reward you long after,
                Insha&rsquo;Allah.
            </p>
            <a href="#donate" class="btn-brand mt-7 px-7 py-3">
                Give Clean Water
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
