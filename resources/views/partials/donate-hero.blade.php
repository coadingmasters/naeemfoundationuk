{{-- Split hero: image + heading on the left, donate widget on the right.
     Params: $heroImage, $heroEyebrow, $heroTitle (HTML), $heroSubtitle, $widgetCauses (array) --}}
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

            <div class="relative w-full p-6 pt-[8.75rem] sm:p-8 sm:pt-[9rem] lg:p-10 lg:pt-40">
                <div class="max-w-lg text-white nf-reveal">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider ring-1 ring-white/20">
                        <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                        {{ $heroEyebrow ?? 'Naeem Foundation' }}
                    </span>
                    <h1 class="mt-4 text-3xl font-extrabold leading-[1.08] sm:text-4xl lg:text-5xl">
                        {!! $heroTitle !!}
                    </h1>
                    @if (!empty($heroSubtitle))
                        <p class="mt-3 max-w-md text-sm leading-relaxed text-white/85 sm:text-base">{{ $heroSubtitle }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Donate widget (extra top padding on desktop so it clears the fixed header).
             Capped width keeps the card compact and gives the photo more space. --}}
        <div class="flex flex-col justify-center px-5 py-8 sm:px-8 lg:px-10 lg:pb-12 lg:pt-40">
            <div class="w-full lg:mx-auto lg:max-w-[28rem]">
                @include('partials.donate-widget', [
                    'widgetCauses' => $widgetCauses ?? ['Where Most Needed'],
                    'widgetImage' => $heroImage,
                ])
            </div>
        </div>
    </div>
</section>
