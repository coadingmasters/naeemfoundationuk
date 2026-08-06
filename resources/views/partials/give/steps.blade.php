{{-- Numbered "how it works" steps with a connecting line.
     Params: $eyebrow, $title, $intro (optional), $steps [ ['title'=>, 'text'=>], ... ] --}}
@php
    $stepCols = match (min(count($steps), 4)) {
        2 => 'lg:grid-cols-2',
        4 => 'lg:grid-cols-4',
        default => 'lg:grid-cols-3',
    };
@endphp
<section class="py-14 sm:py-16">
    <div class="nf-container">
        <div class="nf-reveal mx-auto max-w-2xl text-center">
            @isset($eyebrow)<p class="text-sm font-semibold uppercase tracking-wider text-brand">{{ $eyebrow }}</p>@endisset
            <h2 class="mt-2 text-2xl font-bold text-navy-dark sm:text-3xl">{{ $title }}</h2>
            @isset($intro)<p class="mt-3 text-sm leading-relaxed text-gray-500 sm:text-base">{{ $intro }}</p>@endisset
        </div>

        <div class="relative mt-12 grid gap-8 sm:grid-cols-2 {{ $stepCols }}">
            {{-- connecting line (desktop) --}}
            <span class="pointer-events-none absolute left-0 right-0 top-6 hidden h-px bg-brand/20 lg:block"></span>
            @foreach ($steps as $i => $s)
                <div class="nf-reveal relative" data-reveal-delay="{{ $i * 80 }}">
                    <span class="relative z-10 grid h-12 w-12 place-items-center rounded-full bg-brand text-lg font-extrabold text-white shadow-lg shadow-brand/30">{{ $i + 1 }}</span>
                    <h3 class="mt-4 text-base font-bold text-navy-dark">{{ $s['title'] }}</h3>
                    <p class="mt-1.5 text-sm leading-relaxed text-gray-500">{{ $s['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
