{{-- Big-number stats band.
     Params: $eyebrow, $title, $stats [ ['num'=>, 'label'=>], ... ], $variant ('navy'|'cream') --}}
@php
    $variant = $variant ?? 'navy';
    $isNavy = $variant === 'navy';
    // Static class literals so Tailwind's scanner compiles them.
    $cols = match (min(count($stats), 4)) {
        2 => 'lg:grid-cols-2',
        4 => 'lg:grid-cols-4',
        default => 'lg:grid-cols-3',
    };
@endphp
<section class="{{ $isNavy ? 'bg-navy' : 'bg-cream/50' }} py-14 sm:py-16">
    <div class="nf-container">
        <div class="nf-reveal mx-auto max-w-2xl text-center">
            @isset($eyebrow)
                <p class="text-sm font-semibold uppercase tracking-wider {{ $isNavy ? 'text-[#e9b9c6]' : 'text-brand' }}">{{ $eyebrow }}</p>
            @endisset
            <h2 class="mt-2 text-2xl font-bold {{ $isNavy ? 'text-white' : 'text-navy-dark' }} sm:text-3xl">{{ $title }}</h2>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 {{ $cols }}">
            @foreach ($stats as $i => $s)
                <div class="nf-reveal text-center" data-reveal-delay="{{ $i * 70 }}">
                    <p class="text-4xl font-extrabold {{ $isNavy ? 'text-white' : 'text-brand' }} sm:text-5xl">{{ $s['num'] }}</p>
                    <p class="mt-2 text-sm font-medium {{ $isNavy ? 'text-white/70' : 'text-gray-500' }}">{{ $s['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
