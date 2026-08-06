{{-- FAQ accordion (native <details>, no JS).
     Params: $eyebrow, $title, $faqs [ ['q'=>, 'a'=>], ... ] --}}
<section class="py-14 sm:py-16">
    <div class="nf-container">
        <div class="nf-reveal mx-auto max-w-2xl text-center">
            @isset($eyebrow)<p class="text-sm font-semibold uppercase tracking-wider text-brand">{{ $eyebrow }}</p>@endisset
            <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">{{ $title }}</h2>
        </div>

        <div class="nf-reveal mx-auto mt-9 max-w-3xl space-y-3">
            @foreach ($faqs as $f)
                <details class="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition open:border-brand/40 open:bg-cream/30">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-base font-semibold text-navy-dark">
                        {{ $f['q'] }}
                        <svg class="h-5 w-5 shrink-0 text-brand transition-transform duration-300 group-open:rotate-45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                    </summary>
                    <p class="mt-3 text-sm leading-relaxed text-gray-600">{{ $f['a'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
