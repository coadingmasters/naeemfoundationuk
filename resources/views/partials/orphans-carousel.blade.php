{{-- Continuous, auto-scrolling carousel of orphan cards. Expects $orphans.

     Each card opens that child's profile — the same page the "Sponsor an
     Orphan" grid links to. The track pauses on hover and falls back to a
     manual horizontal scroll under prefers-reduced-motion (see .nf-xscroll). --}}
@php
    $orphans = $orphans ?? collect();
    // The track animates to translateX(-50%), so the list must be duplicated
    // exactly for the loop to be seamless. Scale the speed to the card count.
    $loopOrphans = $orphans->concat($orphans);
    $duration = max(24, $orphans->count() * 6);
@endphp

@if ($orphans->isNotEmpty())
    <div class="nf-xscroll -mx-3" aria-label="Children waiting for a sponsor">
        <div class="nf-xscroll__track" style="animation-duration: {{ $duration }}s">
            @foreach ($loopOrphans as $i => $orphan)
                @php $isClone = $i >= $orphans->count(); @endphp
                <div class="nf-xscroll__item w-[290px] px-3 sm:w-[320px]" @if ($isClone) aria-hidden="true" @endif>
                    {{-- Clones are hidden from assistive tech, so keep them out of
                         the tab order too — a focusable child inside aria-hidden
                         is invalid and traps keyboard users on a duplicate. --}}
                    <a href="{{ route('orphans.show', $orphan) }}" @if ($isClone) tabindex="-1" @endif
                       class="group flex h-full flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:-translate-y-1.5 hover:border-brand/30 hover:shadow-xl hover:shadow-navy/10">
                        {{-- Portrait --}}
                        <div class="relative aspect-[4/5] overflow-hidden bg-gradient-to-b from-cream to-cream/50">
                            @if (filled($orphan->photo))
                                <img src="{{ asset($orphan->photo) }}" alt="{{ $orphan->name }}" loading="lazy"
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
                                    <svg class="h-3.5 w-3.5 shrink-0 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-5.5-7-11a7 7 0 1 1 14 0c0 5.5-7 11-7 11z" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="10" r="2.4"/></svg>
                                    {{ $orphan->location }}
                                </p>
                            @endif

                            <span class="btn-brand mt-4 w-full justify-center py-2.5 text-sm">
                                View &amp; Sponsor
                                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
