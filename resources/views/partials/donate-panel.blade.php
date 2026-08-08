{{-- Reference-style donation panel: One-Off / Monthly / Yearly tabs, currency
     selector, amount (boxes for one-off, single field for recurring), a cause
     dropdown and a UK-only Gift Aid box.
     Params:
       $panelTitle    (string)  card heading
       $causes        (array)   cause dropdown options (first is pre-selected)
       $oneOffAmounts (array)   one-off preset amounts (3 shown + "Other")
       $monthlyDefault(int)     default monthly amount
       $yearlyDefault (int)     default yearly amount
       $orphanId      (int|null) tags the donation to a specific orphan
       $image         (string)  basket thumbnail
       $defaultFreq   (string)  'one-off' (default) | 'monthly' | 'yearly'
       $fundOptions   (array)   optional 2nd dropdown (Sadaqah/Zakat/Lillah…)
       $fundLabel     (string)  heading for that dropdown --}}
@php
    $causes = $causes ?? ['Where Most Needed'];
    // 'dropdown' (default) or 'buttons' — how the cause is chosen.
    $causeStyle = $causeStyle ?? 'dropdown';
    $oneOffAmounts = array_slice(array_values($oneOffAmounts ?? [20, 50, 100]), 0, 3);
    $monthlyDefault = $monthlyDefault ?? 30;
    $yearlyDefault = $yearlyDefault ?? ($monthlyDefault * 12);
    $orphanId = $orphanId ?? null;
    $image = $image ?? 'images/changinslives1.jpg';
    $panelTitle = $panelTitle ?? 'Make a Donation';
    $defaultAmount = $oneOffAmounts[1] ?? $oneOffAmounts[0];
    $sym = region('symbol');

    // Which frequency tab the panel opens on. Rendered server-side so the panel
    // is correct before any JS runs — the script only reacts to clicks.
    $defaultFreq = in_array($defaultFreq ?? 'one-off', ['one-off', 'monthly', 'yearly'], true)
        ? ($defaultFreq ?? 'one-off')
        : 'one-off';
    $isRecurring = $defaultFreq !== 'one-off';
    $recurringValue = $defaultFreq === 'yearly' ? $yearlyDefault : $monthlyDefault;
    $initialAmount = $isRecurring ? $recurringValue : $defaultAmount;

    // Optional second dropdown — the Islamic giving type the gift is made from.
    // Zakat carries strict eligibility rules, so the neutral first option leads.
    $fundOptions = array_values($fundOptions ?? []);
    $fundDefault = $fundOptions[0] ?? null;
    $fundLabel = $fundLabel ?? 'Donation Type';

    // The fund travels inside the cause string — see the note by the dropdown.
    $initialCause = $fundDefault ? $causes[0].' ('.$fundDefault.')' : $causes[0];
@endphp

<form method="POST" action="{{ route('donate.add') }}" data-donate-panel data-cart-skip
      data-monthly="{{ $monthlyDefault }}" data-yearly="{{ $yearlyDefault }}"
      class="rounded-2xl bg-white p-5 shadow-2xl shadow-navy-dark/40 sm:p-6">
    @csrf
    <input type="hidden" name="image" value="{{ $image }}">
    @if ($orphanId)
        <input type="hidden" name="orphan_id" value="{{ $orphanId }}">
    @endif

    <h3 class="text-center text-xl font-bold text-brand sm:text-2xl">{{ $panelTitle }}</h3>

    {{-- Frequency tabs --}}
    <div class="mt-4 grid grid-cols-3 gap-2" data-panel-freq>
        <button type="button" data-freq="one-off" class="nf-choice py-2.5 {{ $defaultFreq === 'one-off' ? 'is-selected' : '' }}">One-Off</button>
        <button type="button" data-freq="monthly" class="nf-choice py-2.5 {{ $defaultFreq === 'monthly' ? 'is-selected' : '' }}">Monthly</button>
        <button type="button" data-freq="yearly" class="nf-choice py-2.5 {{ $defaultFreq === 'yearly' ? 'is-selected' : '' }}">Yearly</button>
        <input type="hidden" name="frequency" data-freq-input value="{{ $defaultFreq }}">
    </div>

    {{-- Amount — one-off boxes --}}
    <div class="mt-4 grid grid-cols-4 gap-2 {{ $isRecurring ? 'hidden' : '' }}" data-amt-oneoff>
        @foreach ($oneOffAmounts as $a)
            <button type="button" data-amt="{{ $a }}" class="nf-choice py-2.5 {{ $a === $defaultAmount ? 'is-selected' : '' }}">{{ $sym }}{{ $a }}</button>
        @endforeach
        <button type="button" data-amt="other" class="nf-choice py-2.5">Other</button>
    </div>

    {{-- Amount — custom (one-off "Other") --}}
    <div class="mt-3 hidden" data-amt-custom>
        <input type="number" min="1" step="1" inputmode="numeric" data-whole-number data-amt-custom-input placeholder="Enter your amount ({{ $sym }})"
               class="h-11 w-full rounded-md border border-gray-300 px-3 text-sm text-navy-dark focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30">
    </div>

    {{-- Amount — recurring single field (monthly / yearly) --}}
    <div class="mt-4 {{ $isRecurring ? '' : 'hidden' }}" data-amt-recurring>
        <div class="relative">
            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 font-bold text-brand">{{ $sym }}</span>
            <input type="number" min="1" step="1" inputmode="numeric" data-whole-number data-amt-recurring-input value="{{ $recurringValue }}"
                   class="h-12 w-full rounded-lg border border-brand/40 pl-7 pr-3 text-center text-base font-bold text-brand focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25">
        </div>
    </div>

    <input type="hidden" name="amount" data-amt-input value="{{ $initialAmount }}">

    {{-- Cause — either a dropdown or a row of selectable buttons. --}}
    <div class="mt-5 text-left">
        <label class="mb-1.5 block text-sm font-bold text-navy-dark">Select Cause</label>
        @if ($causeStyle === 'buttons')
            <div class="grid gap-2 {{ count($causes) === 2 ? 'grid-cols-2' : 'grid-cols-3' }}" data-cause-group>
                @foreach ($causes as $option)
                    <button type="button" data-cause="{{ $option }}" class="nf-choice py-2.5 {{ $loop->first ? 'is-selected' : '' }}">{{ $option }}</button>
                @endforeach
                <input type="hidden" name="cause" data-cause-input data-base="{{ $causes[0] }}" value="{{ $initialCause }}">
            </div>
        @else
            <div class="nf-cselect h-12 rounded-lg border border-navy/20 bg-white" data-cselect>
                <button type="button" class="nf-cselect__btn" data-cselect-btn aria-haspopup="listbox" aria-expanded="false">
                    <span data-cselect-label>{{ $causes[0] }}</span>
                    <svg class="nf-cselect__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <ul class="nf-cselect__menu" role="listbox" data-cselect-menu>
                    @foreach ($causes as $option)
                        <li class="nf-cselect__opt {{ $loop->first ? 'is-selected' : '' }}" role="option" data-value="{{ $option }}">{{ $option }}</li>
                    @endforeach
                </ul>
                <input type="hidden" name="cause" data-cselect-input value="{{ $causes[0] }}">
            </div>
        @endif
    </div>

    {{-- Optional second dropdown: which Islamic giving type the gift comes from.
         The basket stores one cause string per line, so rather than add a field
         that nothing downstream reads, the choice is folded into that string —
         "Alim (Zakat)". It then shows up unchanged in the basket, at checkout,
         on the saved donation, in the admin export and on the email receipt. --}}
    @if ($fundOptions)
        <div class="mt-4 text-left" data-fund-group>
            <label class="mb-1.5 block text-sm font-bold text-navy-dark">{{ $fundLabel }}</label>
            <div class="nf-cselect h-12 rounded-lg border border-navy/20 bg-white" data-cselect>
                <button type="button" class="nf-cselect__btn" data-cselect-btn aria-haspopup="listbox" aria-expanded="false">
                    <span data-cselect-label>{{ $fundDefault }}</span>
                    <svg class="nf-cselect__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <ul class="nf-cselect__menu" role="listbox" data-cselect-menu>
                    @foreach ($fundOptions as $option)
                        <li class="nf-cselect__opt {{ $loop->first ? 'is-selected' : '' }}" role="option" data-value="{{ $option }}">{{ $option }}</li>
                    @endforeach
                </ul>
                {{-- No name: this feeds the cause field above rather than posting itself. --}}
                <input type="hidden" data-cselect-input data-fund-input value="{{ $fundDefault }}">
            </div>
        </div>
    @endif

    {{-- Gift Aid isn't collected here — it's handled on the checkout step. --}}

    <button type="submit" class="btn-navy mt-5 w-full py-3">
        Donate Now
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>

    @include('partials.payment-icons')
</form>
