@extends('layouts.app')

@section('title', 'Qurbani — ' . config('app.name'))

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

    // Qurbani shares the donor can give (currency follows the region).
    $shares = [
        ['amount' => 90, 'label' => 'A Sheep or Goat', 'note' => 'One full Qurbani — feeding a family for days with fresh, quality meat.'],
        ['amount' => 100, 'label' => 'A Cow Share', 'note' => 'A one-seventh share of a larger animal, distributed to those in need.'],
        ['amount' => 630, 'label' => 'A Whole Cow', 'note' => 'Seven Qurbani shares in one — reaching several families at once.'],
    ];

    // What your Qurbani makes possible.
    $impact = [
        ['title' => 'Fresh Meat', 'text' => 'Nutritious, high-quality meat for families who rarely afford it all year.', 'icon' => '<path d="M4 12h4l2 5 4-10 2 5h4" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'A Sunnah Fulfilled', 'text' => 'Following the blessed tradition of Prophet Ibrahim (AS) with sincerity.', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
        ['title' => 'Joy at Eid', 'text' => 'The happiness of a proper Eid meal shared with the most vulnerable.', 'icon' => '<path d="M12 3v2M12 19v2M5 12H3M21 12h-2M6.3 6.3 4.9 4.9M19.1 19.1l-1.4-1.4M6.3 17.7 4.9 19.1M19.1 4.9l-1.4 1.4" stroke-linecap="round"/><circle cx="12" cy="12" r="4"/>'],
        ['title' => 'Dignity & Care', 'text' => 'Every share is distributed fairly, with respect for those who receive it.', 'icon' => '<path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/>'],
    ];
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
            {{-- Left: text --}}
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
            {{-- Right: animated video --}}
            <div class="nf-reveal" data-reveal-delay="120">
                @include('partials.video-card', ['videoKey' => 'qurbani'])
            </div>
        </div>
    </section>

    {{-- ===================== IMPACT ===================== --}}
    <section class="pb-14">
        <div class="nf-container">
            <div class="nf-reveal text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Why it matters</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">What Your Qurbani Brings</h2>
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
            {{-- Left: Qurbani shares --}}
            <div class="nf-reveal">
                <h2 class="text-2xl font-bold text-navy-dark sm:text-3xl">Choose Your Qurbani</h2>
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">
                    Whether you give a single share or a whole animal, your Qurbani is carried out on your behalf and
                    distributed to families most in need. Choose what feels right for you:
                </p>

                <div class="mt-5 space-y-3">
                    @foreach ($shares as $l)
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
                    Prices are a guide and may vary by region. You can give any amount, or a Qurbani in the name of a loved
                    one, using the form above.
                </p>
            </div>

            {{-- Right: image + "join us" card --}}
            <div class="nf-reveal lg:sticky lg:top-28" data-reveal-delay="120">
                <div class="overflow-hidden rounded-2xl bg-cream shadow-sm ring-1 ring-navy/10">
                    <div class="relative h-64 overflow-hidden sm:h-80">
                        <img src="{{ asset('images/changinslives2.jpg') }}" alt="Qurbani meat distribution" class="h-full w-full object-cover">
                        <span class="absolute inset-0 bg-gradient-to-t from-navy-dark/40 to-transparent"></span>
                    </div>
                    <div class="p-6 sm:p-8">
                        <h3 class="text-xl font-bold text-navy-dark">A Sacrifice That Feeds Many</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            For many families, Qurbani meat is the only time all year they enjoy a proper meal. Your gift
                            turns a sacred act of worship into real joy on the plates of those who need it most.
                        </p>
                        <a href="#donate" class="btn-brand mt-5 px-6 py-2.5">
                            Give Your Qurbani
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
            <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Complete your Qurbani this Eid</p>
            <h2 class="mt-2 text-2xl font-bold text-white sm:text-3xl">One Sacrifice, Countless Blessings</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">
                Fulfil your Qurbani with confidence and care. Give today and share the joy of Eid with families who need it
                most — a reward that lasts long after the day itself, Insha&rsquo;Allah.
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
