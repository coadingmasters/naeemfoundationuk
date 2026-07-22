{{-- First-visit region chooser. Shows until the visitor picks a region (cookie set). --}}
@if (! ($regionChosen ?? false))
    <div class="nf-region-pop" role="dialog" aria-modal="true" aria-label="Choose your region">
        <div class="nf-region-pop__card">
            <div class="nf-region-pop__head">
                <span class="nf-region-pop__orb nf-region-pop__orb--1"></span>
                <span class="nf-region-pop__orb nf-region-pop__orb--2"></span>
                <span class="nf-region-pop__globe">🌍</span>
                <h2 class="nf-region-pop__title">Welcome to Naeem Foundation</h2>
                <p class="nf-region-pop__sub">Choose your region so we can show the right currency, phone number and charity details for you.</p>
            </div>

            <div class="nf-region-pop__body">
                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach ($regions as $r)
                        <a href="{{ route('region.set', $r['code']) }}" class="nf-region-pop__tile nf-anim">
                            <span class="nf-region-pop__flag">{{ $r['flag'] }}</span>
                            <span class="nf-region-pop__name">{{ $r['short'] }}</span>
                            <span class="nf-region-pop__cur">{{ $r['symbol'] }} {{ $r['currency'] }}</span>
                            <span class="nf-region-pop__go">
                                Continue
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                        </a>
                    @endforeach
                </div>
                <p class="nf-region-pop__note">You can change your region anytime from the top bar.</p>
            </div>
        </div>
    </div>
@endif
