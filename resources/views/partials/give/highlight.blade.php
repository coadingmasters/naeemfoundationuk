{{-- Split highlight: text + checklist on the left, an accent panel or image on the right.
     Params: $eyebrow, $title, $body (string), $points (array of strings, optional),
             $image (optional path), $variant ('image'|'panel', default 'panel'),
             $reverse (bool, optional — flips sides) --}}
@php
    $variant = $variant ?? 'panel';
    $reverse = $reverse ?? false;
@endphp
<section class="py-14 sm:py-16">
    <div class="nf-container grid items-center gap-10 lg:grid-cols-2 lg:gap-14">
        {{-- Text --}}
        <div class="nf-reveal {{ $reverse ? 'lg:order-2' : '' }}">
            @isset($eyebrow)<p class="text-sm font-semibold uppercase tracking-wider text-brand">{{ $eyebrow }}</p>@endisset
            <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">{{ $title }}</h2>
            <p class="mt-4 text-sm leading-relaxed text-gray-600 sm:text-base">{{ $body }}</p>
            @isset($points)
                <ul class="mt-5 space-y-3">
                    @foreach ($points as $pt)
                        <li class="flex gap-3 text-sm text-gray-700">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span>{{ $pt }}</span>
                        </li>
                    @endforeach
                </ul>
            @endisset
        </div>

        {{-- Accent panel or image --}}
        <div class="nf-reveal {{ $reverse ? 'lg:order-1' : '' }}" data-reveal-delay="120">
            @if ($variant === 'image' && ! empty($image))
                <div class="overflow-hidden rounded-3xl shadow-sm ring-1 ring-navy/10">
                    <div class="relative aspect-[4/3] w-full overflow-hidden">
                        <img src="{{ asset($image) }}" alt="{{ $title }}" class="h-full w-full object-cover">
                        <span class="absolute inset-0 bg-gradient-to-t from-navy-dark/35 to-transparent"></span>
                    </div>
                </div>
            @else
                <div class="rounded-3xl bg-gradient-to-br from-navy via-navy to-navy-dark p-8 text-white shadow-lg sm:p-10">
                    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-white/10 text-white ring-1 ring-white/15">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M12 21s-7.5-4.6-9.5-9A5.2 5.2 0 0 1 12 6.6a5.2 5.2 0 0 1 9.5 5.4c-2 4.4-9.5 9-9.5 9Z"/></svg>
                    </span>
                    <p class="mt-5 text-lg font-semibold leading-relaxed text-white/90">{{ $panelText ?? 'Every contribution, however small, becomes a lifeline for a family who is counting on your compassion.' }}</p>
                    <a href="#donate" class="btn-white mt-6 inline-flex px-6 py-2.5">
                        Donate Now
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
