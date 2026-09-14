{{-- Split hero: image + heading on the left, donate widget on the right.
     Params: $heroImage, $heroTitle (HTML), $widgetCauses (array)
     $widgetPartial (optional, e.g. 'partials.qurbani-panel'): renders this
     instead of the panel/widget for a fully custom form. $widgetPartialData
     (array) is passed to it alongside the usual 'image'.

     The heading now stands alone — the eyebrow pill and subtitle were dropped so
     the title can run large across the space up to the donate panel. Pages still
     pass $heroEyebrow / $heroSubtitle; they're simply ignored, so restoring them
     is a matter of putting the markup back here. --}}
@php
    // An admin upload (Admin -> Hero Banners) overrides the page's hardcoded
    // default, keyed by the current route name — no code change needed to
    // swap a hero photo once a banner is set for this page. A separate
    // mobile-specific photo can also be set there; phones use that instead
    // when present, falling back to the same desktop photo otherwise.
    $routeName = request()->route()?->getName() ?? '';
    $heroImage = \App\Support\PageHeroes::resolve($routeName, $heroImage);
    $mobileHeroImage = \App\Support\PageHeroes::resolveMobile($routeName);
@endphp
<section id="donate" class="relative overflow-hidden">
    {{-- Full-bleed photo behind the whole hero — heading column AND the
         donate panel column — with a single light, even tint so the photo
         stays clearly visible edge to edge instead of fading into a solid
         navy block behind the form. Every hero photo is exported wide
         (1920x450) with its subject on the left and a plain/faded area on
         the right made to be cropped — so the crop anchors left by default.
         $heroImagePosition (optional): overrides this for a photo composed
         differently. --}}
    @if ($mobileHeroImage)
        <img src="{{ asset($mobileHeroImage) }}" alt="" class="absolute inset-0 h-full w-full object-cover {{ $heroImagePosition ?? 'object-left' }} lg:hidden">
        <img src="{{ asset($heroImage) }}" alt="" class="absolute inset-0 hidden h-full w-full object-cover lg:block {{ $heroImagePosition ?? 'object-left' }}">
    @else
        <img src="{{ asset($heroImage) }}" alt="" class="absolute inset-0 h-full w-full object-cover {{ $heroImagePosition ?? 'object-left' }}">
    @endif
    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_bottom,rgba(18,45,60,0.35),rgba(18,45,60,0.55)_45%,rgba(18,45,60,0.4))]"></div>

    <div class="pointer-events-none absolute -right-24 top-0 h-72 w-72 rounded-full bg-brand/25 blur-3xl"></div>
    <div class="pointer-events-none absolute -left-24 -bottom-10 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>

    {{-- Image column is wider than the widget column so the photo gets more
         room and the donation card reads as a compact, professional panel. --}}
    <div class="relative grid items-stretch lg:grid-cols-[1.55fr_1fr]">
        {{-- Heading. The padding-top on this flex box (not on the copy) clears
             the fixed header — 116px on mobile — while leaving the remaining
             space for items-center to balance, so the title sits optically
             centred rather than pinned to the bottom. A long headline still
             grows the panel. --}}
        <div class="relative flex min-h-[440px] items-center pt-24 sm:min-h-[500px] lg:min-h-[580px] lg:pt-28">
            <div class="relative w-full px-6 py-10 sm:px-8 lg:px-10">
                <div class="nf-reveal mx-auto max-w-4xl text-center text-white">
                    <h1 class="nf-hero-title text-4xl font-extrabold leading-[1.04] tracking-tight [text-wrap:balance] sm:text-5xl lg:text-6xl xl:text-[4.25rem]">
                        {!! $heroTitle !!}
                    </h1>
                    {{-- Accent rule draws itself in once the heading lands. --}}
                    <span class="nf-hero-rule mx-auto mt-7 block h-1 rounded-full bg-brand" aria-hidden="true"></span>
                </div>
            </div>
        </div>

        {{-- Donate widget (extra top padding on desktop so it clears the fixed header).
             Capped width keeps the card compact and gives the photo more space. --}}
        <div class="flex flex-col justify-center px-5 py-8 sm:px-8 lg:px-10 lg:pb-12 lg:pt-40">
            <div class="w-full lg:mx-auto lg:max-w-[28rem]">
                @if (! empty($widgetPartial))
                    {{-- Fully custom widget (e.g. a fixed-price package picker
                         that doesn't fit the panel/widget shape). --}}
                    @include($widgetPartial, array_merge(['image' => $heroImage], $widgetPartialData ?? []))
                @elseif (! empty($panel))
                    {{-- Reference-style panel (One-Off/Monthly/Yearly + cause dropdown). --}}
                    @include('partials.donate-panel', array_merge(['image' => $heroImage], $panel))
                @else
                    @include('partials.donate-widget', [
                        'widgetCauses' => $widgetCauses ?? ['Where Most Needed'],
                        'widgetImage' => $heroImage,
                        // Optional "give a whole one" button — see donate-widget.
                        'widgetCtaLabel' => $widgetCtaLabel ?? null,
                        'widgetCtaHref' => $widgetCtaHref ?? null,
                    ])
                @endif
            </div>
        </div>
    </div>
</section>
