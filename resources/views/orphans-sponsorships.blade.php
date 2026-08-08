@extends('layouts.app')

@section('title', 'Sponsor an Orphan — ' . config('app.name'))

{{-- Dark full-bleed video hero, so the header stays transparent (site default). --}}

@php
    use App\Support\VideoSource;

    // Hero background film. Same config entry the click-to-play card used, so
    // swapping the footage in config/appeal-videos.php still drives this page.
    $hero = config('appeal-videos.orphans-sponsorships', config('appeal-videos.default'));
    $heroIsEmbed = VideoSource::isEmbed($hero['url']);

    if ($heroIsEmbed) {
        $embed = VideoSource::embedUrl($hero['url']);

        if (str_contains($embed, 'player.vimeo.com')) {
            // Vimeo has a purpose-built chrome-less background mode.
            $heroSrc = $embed.'?'.http_build_query([
                'background' => 1, 'autoplay' => 1, 'muted' => 1, 'loop' => 1,
            ]);
        } else {
            // YouTube: muted autoplay is the only kind browsers allow, and
            // looping needs the video's own id repeated as a one-item playlist.
            preg_match('#/embed/([\w-]+)#', $embed, $m);
            // Filter on null only — several of these params are a meaningful 0.
            $heroSrc = $embed.'?'.http_build_query(array_filter([
                'autoplay' => 1, 'mute' => 1, 'loop' => 1, 'controls' => 0,
                'playsinline' => 1, 'modestbranding' => 1, 'rel' => 0,
                'disablekb' => 1, 'iv_load_policy' => 3, 'showinfo' => 0,
                'playlist' => $m[1] ?? null,
            ], fn ($p) => $p !== null));
        }
    } else {
        $heroSrc = VideoSource::playableUrl($hero['url']);
    }

    $projects = ($projects ?? collect());
    if ($projects->isEmpty()) {
        $projects = collect([
            (object) ['image' => 'images/changinslives1.jpg', 'title' => 'Education', 'description' => "Empowering tomorrow's leaders through quality, values-based schooling.", 'link' => '#'],
            (object) ['image' => 'images/changinslives4.jpg', 'title' => 'Hostel & Care', 'description' => 'A safe home, warm meals and study support for orphans and students.', 'link' => '#'],
            (object) ['image' => 'images/changinslives2.jpg', 'title' => 'Food', 'description' => 'Food support — our mission to provide for people in need.', 'link' => '#'],
            (object) ['image' => 'images/handpump.jpg', 'title' => 'Clean Water', 'description' => 'Hand pumps and wells bringing safe water to communities in need.', 'link' => '#'],
        ]);
    }
@endphp

@section('content')

    {{-- ===================== HERO: full-bleed background film ===================== --}}
    <section class="relative isolate flex min-h-[560px] items-end overflow-hidden sm:min-h-[620px] lg:min-h-[680px]">

        {{-- Background film, edge to edge. Decorative: the copy below carries
             the meaning, and the player is muted and non-interactive. --}}
        <div class="nf-herovideo" aria-hidden="true">
            <img src="{{ asset($hero['poster']) }}" alt="" class="nf-herovideo__poster">

            @if ($heroIsEmbed)
                <iframe class="nf-herovideo__frame" src="{{ $heroSrc }}" title="{{ $hero['title'] }}"
                        allow="autoplay; encrypted-media; picture-in-picture"
                        referrerpolicy="strict-origin-when-cross-origin"
                        tabindex="-1" frameborder="0" allowfullscreen></iframe>
            @else
                <video class="nf-herovideo__media" poster="{{ asset($hero['poster']) }}"
                       autoplay muted loop playsinline preload="metadata" tabindex="-1">
                    <source src="{{ $heroSrc }}">
                </video>
            @endif
        </div>

        {{-- Layer mix — two stacked gradients so the white copy stays readable
             over any frame of the film, however bright it gets. --}}
        <div class="absolute inset-0 bg-gradient-to-t from-navy-dark via-navy-dark/70 to-navy-dark/40" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-navy-dark/85 via-navy-dark/45 to-transparent" aria-hidden="true"></div>

        {{-- Copy. Top padding clears the fixed header (116px on mobile). --}}
        <div class="nf-container relative w-full pb-14 pt-36 sm:pb-16 sm:pt-40 lg:pb-20 lg:pt-44">
            <div class="nf-reveal max-w-2xl text-white">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-white ring-1 ring-white/20 backdrop-blur-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span> Projects
                </span>
                <h1 class="mt-4 text-3xl font-extrabold leading-tight sm:text-4xl lg:text-5xl">Sponsor an Orphan Today</h1>
                <p class="mt-4 text-sm leading-relaxed text-white/85 sm:text-base">
                    Select a child below to see their story. We offer two ways to help: choose <strong class="font-semibold text-white">monthly
                    support</strong> to cover their ongoing educational and living expenses, or give a one-off gift to
                    contribute to their urgent needs. <strong class="font-semibold text-white">100% of your donation reaches them.</strong>
                </p>
                <a href="#orphans" class="btn-brand group mt-7 px-7 py-3">
                    Sponsor an orphan
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ===================== ORPHAN GRID (AJAX pagination) ===================== --}}
    <section id="orphans" class="py-14 sm:py-16" data-ajax-pagination>
        <div class="nf-container">
            <div class="nf-reveal mb-9 text-center">
                <p class="text-sm font-semibold uppercase tracking-wider text-brand">Every child deserves a chance</p>
                <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">Children Waiting for a Sponsor</h2>
            </div>

            {{-- This container is swapped in place on page changes — no reload. --}}
            <div data-orphan-list>
                @include('partials.orphan-list')
            </div>
        </div>
    </section>

    {{-- ===================== OUR PROJECTS ===================== --}}
    <section class="bg-cream/40 py-16 sm:py-20">
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
