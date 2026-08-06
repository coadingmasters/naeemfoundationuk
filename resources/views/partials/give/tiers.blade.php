{{-- Donation tier cards (pricing-style). One tier can be featured.
     Params: $eyebrow, $title, $intro (optional),
             $tiers [ ['amount'=>, 'label'=>, 'note'=>, 'featured'=>bool], ... ] --}}
<section class="bg-cream/40 py-14 sm:py-16">
    <div class="nf-container">
        <div class="nf-reveal mx-auto max-w-2xl text-center">
            @isset($eyebrow)<p class="text-sm font-semibold uppercase tracking-wider text-brand">{{ $eyebrow }}</p>@endisset
            <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">{{ $title }}</h2>
            @isset($intro)<p class="mt-3 text-sm leading-relaxed text-gray-500 sm:text-base">{{ $intro }}</p>@endisset
        </div>

        <div class="mx-auto mt-10 grid max-w-4xl gap-5 sm:grid-cols-3">
            @foreach ($tiers as $i => $t)
                @php $featured = $t['featured'] ?? false; @endphp
                <div class="nf-reveal flex flex-col rounded-2xl p-6 text-center shadow-sm transition hover:-translate-y-1
                            {{ $featured ? 'bg-navy text-white ring-2 ring-brand' : 'border border-gray-100 bg-white' }}"
                     data-reveal-delay="{{ $i * 70 }}">
                    @if ($featured)
                        <span class="mx-auto mb-3 inline-block rounded-full bg-brand px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-white">Most popular</span>
                    @endif
                    <p class="text-3xl font-extrabold {{ $featured ? 'text-white' : 'text-brand' }} sm:text-4xl">{{ money($t['amount'], 0) }}</p>
                    <p class="mt-2 text-base font-bold {{ $featured ? 'text-white' : 'text-navy-dark' }}">{{ $t['label'] }}</p>
                    <p class="mt-1.5 flex-1 text-sm leading-relaxed {{ $featured ? 'text-white/75' : 'text-gray-500' }}">{{ $t['note'] }}</p>
                    <a href="#donate" class="mt-5 inline-flex items-center justify-center gap-1.5 rounded-lg px-5 py-2.5 text-sm font-bold transition
                            {{ $featured ? 'bg-white text-navy-dark hover:bg-cream' : 'bg-brand text-white hover:bg-brand-dark' }}">
                        Donate {{ money($t['amount'], 0) }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
