{{-- First-visit region chooser. Shows until the visitor picks a region (cookie set). --}}
@if (! ($regionChosen ?? false))
    <div class="nf-region-pop" role="dialog" aria-modal="true" aria-label="Choose your region">
        <div class="nf-region-pop__card">
            <div class="text-center">
                <span class="nf-region-pop__badge">🌍</span>
                <h2 class="mt-4 text-2xl font-extrabold text-navy-dark">Choose your region</h2>
                <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">
                    Select where you’re giving from — we’ll show the right currency, phone number and details for you.
                </p>
            </div>

            <div class="mt-7 grid gap-3 sm:grid-cols-3">
                @foreach ($regions as $r)
                    <a href="{{ route('region.set', $r['code']) }}" class="nf-region-pop__tile">
                        <span class="nf-region-pop__flag">{{ $r['flag'] }}</span>
                        <span class="mt-2 text-base font-bold text-navy-dark">{{ $r['short'] }}</span>
                        <span class="mt-0.5 text-xs font-semibold text-gray-400">{{ $r['symbol'] }} {{ $r['currency'] }}</span>
                        <span class="nf-region-pop__go">
                            Continue
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </a>
                @endforeach
            </div>

            <p class="mt-5 text-center text-xs text-gray-400">You can change this anytime from the top bar.</p>
        </div>
    </div>
@endif
