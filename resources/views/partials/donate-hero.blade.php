{{-- Split hero: image + heading on the left, donate widget on the right.
     Params: $heroImage, $heroTitle (HTML), $widgetCauses (array)

     The heading now stands alone — the eyebrow pill and subtitle were dropped so
     the title can run large across the space up to the donate panel. Pages still
     pass $heroEyebrow / $heroSubtitle; they're simply ignored, so restoring them
     is a matter of putting the markup back here. --}}
<section id="donate" class="relative overflow-hidden bg-gradient-to-br from-navy via-navy to-navy-dark">
    <div class="pointer-events-none absolute -right-24 top-0 h-72 w-72 rounded-full bg-brand/25 blur-3xl"></div>
    <div class="pointer-events-none absolute -left-24 -bottom-10 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>

    {{-- Image column is wider than the widget column so the photo gets more
         room and the donation card reads as a compact, professional panel. --}}
    <div class="relative grid items-stretch lg:grid-cols-[1.55fr_1fr]">
        {{-- Image + heading. The copy sits in normal flow (bottom-aligned via
             items-end) rather than absolutely positioned, so a long headline grows
             the panel instead of spilling upward underneath the fixed header. The
             top padding clears the header, which is 116px tall on mobile. --}}
        <div class="relative flex min-h-[400px] items-end sm:min-h-[440px] lg:min-h-[500px]">
            <img src="{{ asset($heroImage) }}" alt="" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-navy-dark via-navy-dark/45 to-transparent lg:bg-gradient-to-r lg:from-transparent lg:via-navy-dark/20 lg:to-navy"></div>
            {{-- The heading now runs wide across the photo, and on lg the gradient
                 above leaves this side clear — so darken the lower-left behind it. --}}
            <div class="pointer-events-none absolute inset-0 hidden lg:block lg:bg-[linear-gradient(to_top_right,rgba(18,45,60,0.88),rgba(18,45,60,0.45)_38%,transparent_68%)]"></div>

            <div class="relative w-full p-6 pt-[8.75rem] sm:p-8 sm:pt-[9rem] lg:p-10 lg:pt-40">
                <div class="nf-reveal max-w-3xl text-white">
                    <h1 class="nf-hero-title text-4xl font-extrabold leading-[1.04] tracking-tight [text-wrap:balance] sm:text-5xl lg:text-6xl">
                        {!! $heroTitle !!}
                    </h1>
                    {{-- Accent rule draws itself in once the heading lands. --}}
                    <span class="nf-hero-rule mt-6 block h-1 rounded-full bg-brand" aria-hidden="true"></span>
                </div>
            </div>
        </div>

        {{-- Donate widget (extra top padding on desktop so it clears the fixed header).
             Capped width keeps the card compact and gives the photo more space. --}}
        <div class="flex flex-col justify-center px-5 py-8 sm:px-8 lg:px-10 lg:pb-12 lg:pt-40">
            <div class="w-full lg:mx-auto lg:max-w-[28rem]">
                @if (! empty($panel))
                    {{-- Reference-style panel (One-Off/Monthly/Yearly + cause dropdown). --}}
                    @include('partials.donate-panel', array_merge(['image' => $heroImage], $panel))
                @else
                    @include('partials.donate-widget', [
                        'widgetCauses' => $widgetCauses ?? ['Where Most Needed'],
                        'widgetImage' => $heroImage,
                    ])
                @endif
            </div>
        </div>
    </div>
</section>
