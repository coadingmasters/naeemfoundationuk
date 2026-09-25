{{-- Dhul Hajj automated-giving panel: pick a duration — the 10 blessed
     nights of Dhul Hijjah, or however many are left this year — and a daily
     amount, or switch to a plain one-off custom donation instead. Visual
     chrome matches partials/donate-panel / partials/qurbani-panel.
     Params: $image (from donate-hero) --}}
@php
    $nights = (int) config('dhul-hajj.nights');
    $amounts = config('dhul-hajj.amounts');
    $popular = config('dhul-hajj.popular');
    $defaultDaily = (float) config('dhul-hajj.default_daily');

    $starts = \Illuminate\Support\Carbon::parse(config('dhul-hajj.starts_at'));
    $ends = \Illuminate\Support\Carbon::parse(config('dhul-hajj.ends_at'));

    // Before Dhul Hijjah starts this is the full 10 nights; after it ends, 0
    // (the option is hidden below since there's nothing left to schedule).
    $today = \Illuminate\Support\Carbon::today();
    $remainingNights = $today->lt($starts)
        ? $starts->diffInDays($ends) + 1
        : max(0, (int) $today->diffInDays($ends) + 1);
@endphp

<form method="POST" action="{{ route('donate.add') }}" data-cart-skip data-dhj-form
      class="rounded-2xl bg-white p-5 shadow-2xl shadow-navy-dark/40 max-lg:bg-white/55 max-lg:backdrop-blur-sm sm:p-6">
    @csrf
    <input type="hidden" name="image" value="{{ $image }}">
    <input type="hidden" name="frequency" data-dhj-frequency-input value="one-off">
    <input type="hidden" name="amount" data-dhj-amount value="{{ $defaultDaily * $nights }}">
    <input type="hidden" name="cause" data-dhj-cause-input value="Dhul Hajj (10 Nights)">

    <h3 class="text-center text-xl font-bold text-brand sm:text-2xl">Automate Your Giving</h3>

    {{-- One-off vs daily recurring --}}
    <div class="mt-4 grid grid-cols-2 gap-2" data-dhj-freq-group>
        <button type="button" data-dhj-freq="one-off" class="nf-choice py-2.5 text-xs is-selected">One-Off</button>
        <button type="button" data-dhj-freq="daily" class="nf-choice py-2.5 text-xs">Daily Recurring</button>
    </div>

    {{-- Duration / mode --}}
    <div class="mt-3 grid grid-cols-3 gap-2" data-dhj-mode-group>
        <button type="button" data-dhj-mode="10" data-dhj-nights="{{ $nights }}"
                class="nf-choice py-2.5 text-xs is-selected">10 Nights</button>
        @if ($remainingNights > 0)
            <button type="button" data-dhj-mode="remaining" data-dhj-nights="{{ $remainingNights }}"
                    class="nf-choice py-2.5 text-xs">Remaining</button>
        @endif
        <button type="button" data-dhj-mode="custom" class="nf-choice py-2.5 text-xs">Custom</button>
    </div>

    {{-- Daily amount — shown for the two nights-based modes --}}
    <div class="mt-4" data-dhj-daily>
        <label class="mb-1.5 block text-sm font-bold text-navy-dark">Daily amount</label>
        <div class="grid grid-cols-4 gap-2">
            @foreach ($amounts as $amount)
                <button type="button" data-dhj-amount-choice="{{ $amount }}"
                        class="nf-choice py-2 {{ (float) $amount === $defaultDaily ? 'is-selected' : '' }}">{{ region('symbol') }}{{ $amount }}</button>
            @endforeach
        </div>
        <p class="mt-3 text-sm font-semibold text-brand">
            <span data-dhj-total>{{ region('symbol') }}{{ number_format($defaultDaily * $nights, 2) }}</span>
            <span class="font-normal text-gray-500" data-dhj-total-suffix>for {{ $nights }} nights</span>
        </p>
    </div>

    {{-- Custom amount — shown for the "Custom" mode instead --}}
    <div class="mt-3 hidden" data-dhj-custom>
        <input type="number" min="1" step="1" inputmode="numeric" data-whole-number data-dhj-custom-input
               placeholder="Enter your amount ({{ region('symbol') }})"
               class="h-11 w-full rounded-md border border-gray-300 px-3 text-sm text-navy-dark focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30">
    </div>

    <p class="mt-3 hidden text-xs leading-relaxed text-gray-500" data-dhj-recur-note>
        PayPal will automatically take this each day until you cancel — you can cancel any time.
    </p>

    <button type="submit" class="btn-navy mt-5 w-full py-3">
        Donate Now
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>

    @include('partials.payment-icons')
</form>

@push('scripts')
<script>
    // Dhul Hajj panel — duration buttons pick between the 10 blessed nights,
    // however many remain, or a plain custom one-off amount; the daily
    // amount presets only apply to the two nights-based modes.
    (function () {
        const form = document.querySelector('[data-dhj-form]');
        if (!form) return;

        const amountInput = form.querySelector('[data-dhj-amount]');
        const frequencyInput = form.querySelector('[data-dhj-frequency-input]');
        const causeInput = form.querySelector('[data-dhj-cause-input]');
        const dailyWrap = form.querySelector('[data-dhj-daily]');
        const customWrap = form.querySelector('[data-dhj-custom]');
        const customInput = form.querySelector('[data-dhj-custom-input]');
        const totalEl = form.querySelector('[data-dhj-total]');
        const totalSuffixEl = form.querySelector('[data-dhj-total-suffix]');
        const recurNoteEl = form.querySelector('[data-dhj-recur-note]');
        const freqBtns = [...form.querySelectorAll('[data-dhj-freq]')];
        const modeBtns = [...form.querySelectorAll('[data-dhj-mode]')];
        const amountBtns = [...form.querySelectorAll('[data-dhj-amount-choice]')];
        const sym = '{{ region("symbol") }}';

        let freq = 'one-off';
        let mode = modeBtns[0]?.dataset.dhjMode ?? '10';
        let nights = Number(modeBtns[0]?.dataset.dhjNights) || {{ $nights }};
        let daily = {{ $defaultDaily }};

        const money = (n) => `${sym}${n.toFixed(2)}`;
        const modeLabel = () => (mode === '10' ? '10 Nights' : mode === 'remaining' ? 'Remaining Nights' : 'Custom Donation');

        const render = () => {
            if (frequencyInput) frequencyInput.value = freq;
            if (recurNoteEl) recurNoteEl.classList.toggle('hidden', freq !== 'daily');

            if (mode === 'custom') {
                amountInput.value = customInput.value || '';
                causeInput.value = freq === 'daily' ? 'Dhul Hajj (Daily Giving)' : `Dhul Hajj (${modeLabel()})`;
            } else {
                const total = freq === 'daily' ? daily : daily * nights;
                if (totalEl) totalEl.textContent = money(total);
                if (totalSuffixEl) totalSuffixEl.textContent = freq === 'daily' ? 'per day' : `for ${nights} nights`;
                amountInput.value = total.toFixed(2);
                amountBtns.forEach((b) => b.classList.toggle('is-selected', Number(b.dataset.dhjAmountChoice) === daily));
                causeInput.value = freq === 'daily' ? 'Dhul Hajj (Daily Giving)' : `Dhul Hajj (${modeLabel()})`;
            }
        };

        freqBtns.forEach((btn) => btn.addEventListener('click', () => {
            freq = btn.dataset.dhjFreq;
            freqBtns.forEach((b) => b.classList.toggle('is-selected', b === btn));
            render();
        }));

        modeBtns.forEach((btn) => btn.addEventListener('click', () => {
            mode = btn.dataset.dhjMode;
            modeBtns.forEach((b) => b.classList.toggle('is-selected', b === btn));

            if (mode === 'custom') {
                dailyWrap.classList.add('hidden');
                customWrap.classList.remove('hidden');
                customInput.focus();
            } else {
                nights = Number(btn.dataset.dhjNights);
                dailyWrap.classList.remove('hidden');
                customWrap.classList.add('hidden');
            }
            render();
        }));

        amountBtns.forEach((btn) => btn.addEventListener('click', () => {
            daily = Number(btn.dataset.dhjAmountChoice);
            render();
        }));

        customInput?.addEventListener('input', () => {
            customInput.value = customInput.value.replace(/\D/g, '');
            render();
        });

        form.addEventListener('submit', (e) => {
            if (!amountInput.value || Number(amountInput.value) < 1) {
                e.preventDefault();
                if (mode === 'custom') customInput.focus();
            }
        });

        render();
    })();
</script>
@endpush
