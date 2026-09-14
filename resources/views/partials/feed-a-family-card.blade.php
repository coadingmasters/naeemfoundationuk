{{-- "Feed a Family for a Month" donation card — amount presets, per-tier
     bullets, and the one-off/monthly switch. Shared by Food & Sustenance and
     Food Appeal so both pages behave identically. No params required. --}}
@php
    // Preset amounts. The full month's ration is the default selection.
    $familyCost = 100;
    $familyAmounts = [50, 70, $familyCost];

    // What each preset amount feeds, shown as a bullet under the amount picker.
    $familyTiers = [
        50 => 'Feeds a family of 3-4 people for a month.',
        70 => 'Feeds a family of 4-6 people for a month.',
        100 => 'Feeds a family of 6-8 people for a month.',
    ];

    // Currencies come from the region config so this list can never claim
    // something the region switcher and PayPal don't actually support.
    $currencies = collect(config('countries.list', []))->pluck('currency')->implode(', ');
@endphp
<section class="pb-14 sm:pb-16">
    <div class="nf-container">
        <div class="nf-reveal overflow-hidden rounded-3xl bg-navy shadow-2xl shadow-navy/25">
            <div class="grid lg:grid-cols-[1.15fr_1fr]">

                {{-- Left: the details + the single amount --}}
                <div class="p-7 text-white sm:p-10 lg:p-12">
                    <p class="text-sm font-semibold uppercase tracking-wider text-[#e9b9c6]">Join Us in Nourishing Lives</p>
                    <h2 class="mt-2 text-2xl font-bold leading-snug sm:text-3xl">Feed a Family for a Month</h2>
                    <p class="mt-4 text-sm leading-relaxed text-white/80 sm:text-base">
                        Your generous donation can make a tangible difference in the lives of those struggling to put
                        food on the table. By supporting our food assistance programs, you are helping to alleviate
                        hunger, promote health, and empower individuals to build a better future.
                    </p>

                    <form method="POST" action="{{ route('donate.add') }}" data-cart-skip data-family-form class="mt-7">
                        @csrf
                        <input type="hidden" name="cause" value="Food & Sustenance">
                        <input type="hidden" name="image" value="images/Food-1920.jpg">
                        <input type="hidden" name="amount" value="{{ $familyCost }}" data-family-amount-input>
                        <input type="hidden" name="frequency" value="one-off" data-family-freq>

                        {{-- Amount --}}
                        <p class="text-xs font-semibold uppercase tracking-wider text-white/70">Choose an amount</p>
                        <div class="mt-3 grid grid-cols-3 gap-2 sm:max-w-md">
                            @foreach ($familyAmounts as $a)
                                <button type="button" data-family-amount="{{ $a }}"
                                        class="nf-limb-choice {{ $a === $familyCost ? 'is-selected' : '' }}">{{ money($a, 0) }}</button>
                            @endforeach
                        </div>

                        {{-- What each preset amount feeds — one line per tier, so the
                             donor sees this regardless of which amount they pick. --}}
                        <ul class="mt-3 space-y-1.5 text-xs leading-relaxed text-white/65 sm:text-sm">
                            @foreach ($familyAmounts as $a)
                                <li class="flex items-start gap-2">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#e9b9c6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01" stroke-linecap="round"/></svg>
                                    <span><span class="font-semibold text-white">{{ money($a, 0) }}</span> — {{ $familyTiers[$a] }}</span>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Give once, or every month (the recurring option below). --}}
                        <p class="mt-6 text-xs font-semibold uppercase tracking-wider text-white/70">How often?</p>
                        <div class="mt-3 grid grid-cols-2 gap-2 sm:max-w-xs">
                            <button type="button" data-family-choice="one-off" class="nf-limb-choice is-selected">One-Off</button>
                            <button type="button" data-family-choice="monthly" class="nf-limb-choice">Monthly</button>
                        </div>

                        <button type="submit" class="btn-brand group mt-6 w-full justify-center py-3.5 text-base sm:w-auto sm:px-9">
                            Donate <span data-family-total>{{ money($familyCost, 0) }}</span>
                            <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </form>

                    {{-- Donation options --}}
                    <div class="mt-8 border-t border-white/15 pt-6">
                        <h3 class="text-base font-bold text-white">Donation Options</h3>
                        <ul class="mt-3 space-y-2.5 text-sm text-white/75">
                            @foreach ([
                                ['Single Donations:', 'Suggested amounts '.implode(', ', array_map(fn ($a) => money($a, 0), $familyAmounts)).'.'],
                                ['Recurring Donations:', 'Set up a monthly contribution to feed a family every month.'],
                                ['Currency Options:', $currencies.' — set by your region at the top of the page.'],
                            ] as $j => $opt)
                                <li class="nf-reveal flex gap-2" data-reveal-delay="{{ 140 + $j * 90 }}">
                                    <span class="font-semibold text-[#e9b9c6]">&rsaquo;</span>
                                    <span><span class="font-semibold text-white">{{ $opt[0] }}</span> {{ $opt[1] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Right: image --}}
                <div class="relative min-h-[280px] overflow-hidden lg:min-h-full">
                    <img src="{{ asset('images/Food-1920.jpg') }}" alt="A family receiving a food ration pack"
                         class="absolute inset-0 h-full w-full object-cover transition-transform duration-[1.4s] ease-out hover:scale-105">
                    {{-- Blends the photo into the navy card on the left edge. --}}
                    <span class="pointer-events-none absolute inset-0 bg-gradient-to-t from-navy/60 to-transparent lg:bg-gradient-to-r lg:from-navy lg:via-navy/25 lg:to-transparent" aria-hidden="true"></span>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Feed a Family card — amount presets plus the one-off / monthly switch.
    // The submitted value always comes from the hidden input, never a label.
    // Scoped to `data-family-form` — safe even if a page somehow rendered
    // this partial more than once, since each handler binds to its own form.
    document.querySelectorAll('[data-family-form]').forEach((form) => {
        const freq = form.querySelector('[data-family-freq]');
        const amountInput = form.querySelector('[data-family-amount-input]');
        const total = form.querySelector('[data-family-total]');
        const amounts = [...form.querySelectorAll('[data-family-amount]')];
        const freqBtns = [...form.querySelectorAll('[data-family-choice]')];

        amounts.forEach((btn) => btn.addEventListener('click', () => {
            amounts.forEach((b) => b.classList.toggle('is-selected', b === btn));
            amountInput.value = btn.dataset.familyAmount;
            // Mirror the chosen amount on the button so it always agrees.
            if (total) total.textContent = btn.textContent.trim();
        }));

        freqBtns.forEach((btn) => btn.addEventListener('click', () => {
            freqBtns.forEach((b) => b.classList.toggle('is-selected', b === btn));
            freq.value = btn.dataset.familyChoice;
        }));
    });
</script>
@endpush
