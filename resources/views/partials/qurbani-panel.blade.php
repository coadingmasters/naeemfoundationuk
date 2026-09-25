{{-- Qurbani donation panel: fixed-price package picker (Goat / Cow / Cow
     Share / Other) instead of the usual One-Off/Monthly amount picker —
     Qurbani is a single sacrifice, not a recurring gift — plus a recipient
     dropdown. Visual chrome matches partials/donate-panel for consistency.
     Params: $image (from donate-hero) --}}
@php
    $packages = [
        ['label' => 'Goat', 'amount' => 145],
        ['label' => 'Cow', 'amount' => 630],
        ['label' => 'Cow Share', 'amount' => 90],
    ];
    $recipients = ['Needy', 'Orphans', 'Binoria Students'];
    $default = $packages[0];
@endphp

<form method="POST" action="{{ route('donate.add') }}" data-cart-skip data-qurbani-form
      class="rounded-2xl bg-white p-5 shadow-2xl shadow-navy-dark/40 max-lg:bg-white/55 max-lg:backdrop-blur-sm sm:p-6">
    @csrf
    <input type="hidden" name="image" value="{{ $image }}">
    <input type="hidden" name="frequency" value="one-off">
    <input type="hidden" name="amount" data-qurbani-amount value="{{ $default['amount'] }}">
    <input type="hidden" name="cause" data-qurbani-cause-input
           value="{{ $default['label'] }} Qurbani ({{ $recipients[0] }})">

    <h3 class="text-center text-xl font-bold text-brand sm:text-2xl">Give Your Qurbani</h3>

    {{-- Package --}}
    <div class="mt-4">
        <label class="mb-1.5 block text-sm font-bold text-navy-dark">Choose a package</label>
        <div class="grid grid-cols-2 gap-2" data-qurbani-package>
            @foreach ($packages as $p)
                <button type="button" data-qurbani-choice="{{ $p['amount'] }}" data-qurbani-label="{{ $p['label'] }} Qurbani"
                        class="nf-choice py-2.5 {{ $loop->first ? 'is-selected' : '' }}">{{ $p['label'] }}</button>
            @endforeach
            <button type="button" data-qurbani-choice="other" data-qurbani-label="Qurbani" class="nf-choice py-2.5">Other</button>
        </div>
        <p class="mt-3 text-sm font-semibold text-brand" data-qurbani-price>
            <span data-qurbani-price-amount>{{ region('symbol') }}{{ $default['amount'] }}</span>
            <span class="font-normal text-gray-500">for this package</span>
        </p>
    </div>

    {{-- Amount — custom ("Other") --}}
    <div class="mt-3 hidden" data-qurbani-custom>
        <input type="number" min="1" step="1" inputmode="numeric" data-whole-number data-qurbani-custom-input
               placeholder="Enter your amount ({{ region('symbol') }})"
               class="h-11 w-full rounded-md border border-gray-300 px-3 text-sm text-navy-dark focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30">
    </div>

    {{-- Recipient --}}
    <div class="mt-5 text-left">
        <label class="mb-1.5 block text-sm font-bold text-navy-dark">Select Cause</label>
        <div class="nf-cselect h-12 rounded-lg border border-navy/20 bg-white" data-cselect>
            <button type="button" class="nf-cselect__btn" data-cselect-btn aria-haspopup="listbox" aria-expanded="false">
                <span data-cselect-label>{{ $recipients[0] }}</span>
                <svg class="nf-cselect__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <ul class="nf-cselect__menu" role="listbox" data-cselect-menu>
                @foreach ($recipients as $option)
                    <li class="nf-cselect__opt {{ $loop->first ? 'is-selected' : '' }}" role="option" data-value="{{ $option }}">{{ $option }}</li>
                @endforeach
            </ul>
            {{-- No name: this feeds the cause field above rather than posting itself. --}}
            <input type="hidden" data-cselect-input data-qurbani-recipient value="{{ $recipients[0] }}">
        </div>
    </div>

    <button type="submit" class="btn-navy mt-5 w-full py-3">
        Donate Now
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>

    @include('partials.payment-icons')
</form>

@push('scripts')
<script>
    // Qurbani panel — package buttons set a fixed amount (or reveal a custom
    // field for "Other"); the recipient dropdown is folded into the cause
    // string alongside the package, e.g. "Cow Qurbani (Orphans)".
    (function () {
        const form = document.querySelector('[data-qurbani-form]');
        if (!form) return;

        const amount = form.querySelector('[data-qurbani-amount]');
        const causeInput = form.querySelector('[data-qurbani-cause-input]');
        const priceHint = form.querySelector('[data-qurbani-price]');
        const priceHintAmount = form.querySelector('[data-qurbani-price-amount]');
        const customWrap = form.querySelector('[data-qurbani-custom]');
        const customInput = form.querySelector('[data-qurbani-custom-input]');
        const recipientInput = form.querySelector('[data-qurbani-recipient]');
        const choices = [...form.querySelectorAll('[data-qurbani-choice]')];
        const sym = '{{ region("symbol") }}';

        let currentLabel = choices[0]?.dataset.qurbaniLabel ?? 'Qurbani';

        const updateCause = () => {
            const recipient = recipientInput?.value || '{{ $recipients[0] }}';
            causeInput.value = `${currentLabel} (${recipient})`;
        };

        choices.forEach((btn) => btn.addEventListener('click', () => {
            choices.forEach((b) => b.classList.toggle('is-selected', b === btn));
            currentLabel = btn.dataset.qurbaniLabel;

            if (btn.dataset.qurbaniChoice === 'other') {
                customWrap.classList.remove('hidden');
                priceHint.classList.add('hidden');
                customInput.focus();
                amount.value = customInput.value || '';
            } else {
                customWrap.classList.add('hidden');
                priceHint.classList.remove('hidden');
                priceHintAmount.textContent = sym + btn.dataset.qurbaniChoice;
                amount.value = btn.dataset.qurbaniChoice;
            }
            updateCause();
        }));

        customInput?.addEventListener('input', () => {
            // Whole pounds only, matching the rest of the donation forms.
            customInput.value = customInput.value.replace(/\D/g, '');
            amount.value = customInput.value;
        });

        // The recipient dropdown is the shared data-cselect component (see
        // app.js) — it sets the hidden input's value via a plain JS property
        // assignment with no change event, so re-read it on the same click,
        // deferred to run after that handler (already registered first).
        form.querySelectorAll('.nf-cselect__opt').forEach((opt) => {
            opt.addEventListener('click', () => setTimeout(updateCause, 0));
        });

        form.addEventListener('submit', (e) => {
            if (!amount.value || Number(amount.value) < 1) {
                e.preventDefault();
                customWrap.classList.remove('hidden');
                priceHint.classList.add('hidden');
                customInput.focus();
            }
        });
    })();
</script>
@endpush
