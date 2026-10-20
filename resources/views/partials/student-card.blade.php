{{-- A sponsorship student's card (from the Binoria API). Expects $orphan, shaped by
     OrphanController::asCards(). Used by the sponsorship grid and the carousel. --}}
@php
    $monthly = $orphan->monthly_amount ?: 53;
    $facts = collect([$orphan->age, $orphan->gender, 'Students'])->filter()->implode(' | ');
    // Binoria's "Zakat Eligible" reads as a short tag, like the "Zakat" pill on the reference.
    $tag = $orphan->grade === 'Zakat Eligible' ? 'Zakat' : $orphan->grade;
@endphp

<a href="{{ $orphan->url }}" @if (! empty($noTab)) tabindex="-1" @endif
   class="group flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand/30 hover:shadow-lg hover:shadow-navy/10 sm:p-5">

    {{-- Who --}}
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <h3 class="line-clamp-2 text-sm font-bold uppercase leading-snug tracking-wide text-navy-dark transition-colors group-hover:text-brand sm:text-base">{{ $orphan->name }}</h3>
            <p class="mt-2 text-xs text-gray-600">{{ $facts }}</p>
            @if ($orphan->is_orphan)
                <p class="mt-0.5 text-xs text-gray-600">Orphan Student</p>
            @endif
            @if ($orphan->country)
                <p class="mt-1.5 flex items-center gap-1.5 text-[11px] text-gray-600">
                    @if ($orphan->flag)<span class="text-sm leading-none">{{ $orphan->flag }}</span>@endif
                    <span class="truncate">{{ $orphan->country }}</span>
                </p>
            @endif
        </div>
        <span class="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-slate-100 text-navy-dark sm:h-16 sm:w-16">
            <svg class="h-8 w-8 sm:h-9 sm:w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M22 10L12 5 2 10l10 5 10-5zM6 12v5c0 1 3 2 6 2s6-1 6-2v-5M22 10v6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </span>
    </div>

    {{-- Tag --}}
    @if ($tag)
        <div class="mt-3">
            <span class="inline-block rounded-md bg-brand/10 px-3 py-1.5 text-[11px] font-bold text-brand">{{ $tag }}</span>
        </div>
    @endif

    {{-- Value + action --}}
    <div class="mt-auto pt-4">
        <div class="flex items-center justify-between gap-3 border-t border-gray-200 pt-4">
            <div>
                <p class="text-[11px] text-gray-500">Monthly sponsorship value</p>
                <p class="mt-0.5 text-lg font-bold text-navy-dark">{{ region('symbol') }}{{ number_format($monthly, 2) }}</p>
            </div>
            <span class="inline-flex shrink-0 items-center justify-center rounded-lg bg-brand px-5 py-2.5 text-xs font-bold text-white transition group-hover:bg-brand-dark">
                Sponsor Me
            </span>
        </div>
    </div>
</a>
