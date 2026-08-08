{{-- Reusable donation widget — matches the Zakat page card.
     Params:
       $widgetCauses (array)  first entry becomes the fixed cause for this page
       $widgetImage  (string) basket thumbnail
       $widgetAmounts (array) four suggested amounts; the last renders as "Other"
       $widgetTitle  (string) card heading
       $widgetCtaLabel / $widgetCtaHref (string, optional) — a secondary button
             under "Donate Now" that sends the donor to a fixed-price section
             further down the page (e.g. a full limb, a full water source). --}}
@php
    $widgetCauses = $widgetCauses ?? ['Where Most Needed'];
    $widgetImage = $widgetImage ?? 'images/changinslives1.jpg';
    // When set, the donation line is tagged with this orphan so admins can see
    // exactly which child received the money.
    $orphanId = $orphanId ?? null;
    $widgetAmounts = $widgetAmounts ?? [50, 100, 250];
    // Smaller presets shown when the donor switches to a Monthly gift.
    $widgetMonthlyAmounts = $widgetMonthlyAmounts ?? [10, 25, 50];
    $widgetTitle = $widgetTitle ?? 'Choose an amount';

    $cause = $widgetCauses[0];
    // Show three preset amounts, then an "Other" button. Pick a sensible default.
    $presets = array_slice(array_values($widgetAmounts), 0, 3);
    $monthlyPresets = array_slice(array_values($widgetMonthlyAmounts), 0, 3);
    $defaultAmount = $presets[1] ?? $presets[0];
@endphp

<form method="POST" action="{{ route('donate.add') }}" data-donate-form
      class="rounded-2xl bg-white p-5 shadow-2xl shadow-navy-dark/40 sm:p-6">
    @csrf
    <input type="hidden" name="image" value="{{ $widgetImage }}">
    @if ($orphanId)
        <input type="hidden" name="orphan_id" value="{{ $orphanId }}">
    @endif

    <p class="text-center text-sm font-bold uppercase tracking-wide text-brand">{{ $widgetTitle }}</p>

    {{-- Cause dropdown — the donor can give to any cause from here. --}}
    @include('partials.cause-select', ['selectedCause' => $cause])

    {{-- Frequency --}}
    <div class="mt-4 grid grid-cols-2 gap-3" data-choice-group>
        <button type="button" data-choice data-value="one-off" class="nf-choice is-selected py-2.5">One-Off</button>
        <button type="button" data-choice data-value="monthly" class="nf-choice py-2.5">Monthly</button>
        <input type="hidden" name="frequency" data-choice-input value="one-off">
    </div>

    {{-- Amounts --}}
    <div class="mt-3 grid grid-cols-4 gap-2" data-choice-group>
        @foreach ($presets as $i => $amount)
            <button type="button" data-choice data-value="{{ $amount }}"
                    data-oneoff="{{ $amount }}" data-monthly="{{ $monthlyPresets[$i] ?? $amount }}"
                    class="nf-choice py-2 {{ $amount === $defaultAmount ? 'is-selected' : '' }}">{{ region('symbol') }}{{ $amount }}</button>
        @endforeach
        <button type="button" data-choice data-value="other" class="nf-choice py-2">Other</button>
        <input type="hidden" name="amount" data-choice-input data-amount-input value="{{ $defaultAmount }}">
    </div>

    {{-- Custom amount (revealed when "Other" is chosen) --}}
    <div data-custom-amount class="mt-3 hidden">
        <label class="mb-1.5 block text-sm font-semibold text-navy-dark">Enter your amount</label>
        <input type="number" min="1" step="1" inputmode="numeric" data-whole-number placeholder="e.g. 75" data-custom-amount-input
               class="h-11 w-full rounded-md border border-gray-300 px-3 text-sm text-navy-dark focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30">
    </div>

    {{-- Donate --}}
    <button type="submit" class="btn-navy mt-5 w-full py-3">
        Donate Now
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>

    {{-- Optional second action: jump to the fixed-price section below. It's an
         anchor, not a submit, so it never posts this form. --}}
    @if (! empty($widgetCtaLabel) && ! empty($widgetCtaHref))
        <a href="{{ $widgetCtaHref }}"
           class="group mt-2.5 inline-flex w-full items-center justify-center gap-1.5 rounded-md border border-brand/40 px-4 py-3 text-sm font-bold text-brand transition-colors hover:bg-brand hover:text-white">
            {{ $widgetCtaLabel }}
            <svg class="h-4 w-4 transition-transform duration-300 group-hover:translate-y-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12l7 7 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    @endif

    @include('partials.payment-icons')
</form>
