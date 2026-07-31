{{-- Orphan cards grid + pagination. Rendered on first load AND returned on its
     own for AJAX page changes (see OrphanController + setupAjaxPagination). --}}
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @foreach ($orphans as $orphan)
        <a href="{{ route('orphans.show', $orphan) }}"
           class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1.5 hover:border-brand/30 hover:shadow-xl hover:shadow-navy/10">
            {{-- Portrait --}}
            <div class="relative aspect-[4/5] overflow-hidden bg-gradient-to-b from-cream to-cream/50">
                @if (filled($orphan->photo))
                    <img src="{{ asset($orphan->photo) }}" alt="{{ $orphan->name }}"
                         class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                @else
                    <div class="grid h-full place-items-center">
                        <svg class="h-20 w-20 text-navy/15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4 0-8 2-8 5v1h16v-1c0-3-4-5-8-5z"/></svg>
                    </div>
                @endif
                <span class="pointer-events-none absolute inset-0 bg-gradient-to-t from-navy-dark/45 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>
                @if ($orphan->grade)
                    <span class="absolute left-3 top-3 rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold text-brand shadow-sm backdrop-blur">{{ $orphan->grade }}</span>
                @endif
            </div>

            {{-- Details --}}
            <div class="flex flex-1 flex-col p-5">
                <h3 class="text-base font-bold text-navy-dark transition-colors group-hover:text-brand sm:text-lg">{{ $orphan->name }}</h3>
                @if ($orphan->location)
                    <p class="mt-1.5 inline-flex items-center gap-1.5 text-xs text-gray-500">
                        <svg class="h-3.5 w-3.5 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11z" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="10" r="2.4"/></svg>
                        {{ $orphan->location }}
                    </p>
                @endif

                <span class="btn-brand mt-4 w-full justify-center py-2.5 text-sm">
                    View &amp; Sponsor
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
            </div>
        </a>
    @endforeach
</div>

@if ($orphans->hasPages())
    <div class="mt-10">
        {{ $orphans->onEachSide(1)->links() }}
    </div>
@endif
