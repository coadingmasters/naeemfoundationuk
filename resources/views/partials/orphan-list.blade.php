{{-- Orphan cards grid + pagination. Rendered on first load AND returned on its
     own for AJAX page changes (see OrphanController + setupAjaxPagination). --}}
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @foreach ($orphans as $orphan)
        <div class="group flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg hover:shadow-navy/10">
            {{-- Portrait --}}
            <div class="aspect-[4/5] overflow-hidden bg-gradient-to-b from-cream to-cream/50">
                @if (! empty($orphan['photo']))
                    <img src="{{ asset($orphan['photo']) }}" alt="{{ $orphan['name'] }}"
                         class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                @else
                    <div class="grid h-full place-items-center">
                        <svg class="h-20 w-20 text-navy/15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4 0-8 2-8 5v1h16v-1c0-3-4-5-8-5z"/></svg>
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div class="flex flex-1 flex-col p-5">
                <h3 class="text-base font-bold text-navy-dark">{{ $orphan['name'] }}</h3>
                <p class="mt-1.5 text-xs text-gray-500"><span class="font-semibold text-brand">Location:</span> {{ $orphan['location'] }}</p>
                <p class="mt-0.5 text-xs text-gray-500"><span class="font-semibold text-brand">Grade:</span> {{ $orphan['grade'] }}</p>

                <a href="{{ route('donate.make', ['fund' => 'Sponsor ' . $orphan['name']]) }}"
                   class="btn-brand mt-4 w-full justify-center py-2.5 text-sm">
                    Sponsor orphan now
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    @endforeach
</div>

@if ($orphans->hasPages())
    <div class="mt-10">
        {{ $orphans->onEachSide(1)->links() }}
    </div>
@endif
