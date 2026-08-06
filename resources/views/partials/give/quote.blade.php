{{-- Large centered pull-quote / testimonial.
     Params: $quote, $author (optional), $role (optional), $variant ('navy'|'cream') --}}
@php
    $variant = $variant ?? 'cream';
    $isNavy = $variant === 'navy';
@endphp
<section class="{{ $isNavy ? 'bg-navy' : 'bg-cream/60' }} py-16 sm:py-20">
    <div class="nf-container">
        <figure class="nf-reveal mx-auto max-w-3xl text-center">
            <svg class="mx-auto h-10 w-10 {{ $isNavy ? 'text-brand' : 'text-brand/40' }}" viewBox="0 0 24 24" fill="currentColor"><path d="M9.5 7C6.5 7 4 9.5 4 12.5V19h7v-7H7.5c0-1.6 1-2.5 2-2.5V7zm9 0c-3 0-5.5 2.5-5.5 5.5V19h7v-7H16.5c0-1.6 1-2.5 2-2.5V7z"/></svg>
            <blockquote class="mt-5 text-xl font-semibold leading-relaxed {{ $isNavy ? 'text-white' : 'text-navy-dark' }} sm:text-2xl lg:text-[1.7rem]">
                &ldquo;{{ $quote }}&rdquo;
            </blockquote>
            @isset($author)
                <figcaption class="mt-6">
                    <span class="block text-sm font-bold {{ $isNavy ? 'text-white' : 'text-brand' }}">{{ $author }}</span>
                    @isset($role)<span class="block text-xs {{ $isNavy ? 'text-white/60' : 'text-gray-500' }}">{{ $role }}</span>@endisset
                </figcaption>
            @endisset
        </figure>
    </div>
</section>
