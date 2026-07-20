{{-- Reusable donation widget — matches the Zakat page card.
     Params:
       $widgetCauses (array)  first entry becomes the fixed cause for this page
       $widgetImage  (string) basket thumbnail
       $widgetAmounts (array) four suggested amounts; the last renders as "Other"
       $widgetTitle  (string) card heading --}}
@php
    $widgetCauses = $widgetCauses ?? ['Where Most Needed'];
    $widgetImage = $widgetImage ?? 'images/changinslives1.jpg';
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
    <input type="hidden" name="cause" value="{{ $cause }}">
    <input type="hidden" name="image" value="{{ $widgetImage }}">

    <p class="text-center text-sm font-bold uppercase tracking-wide text-brand">{{ $widgetTitle }}</p>
    <p class="mt-1 text-center text-xs text-gray-500">Supporting <span class="font-semibold text-navy-dark">{{ $cause }}</span></p>

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
                    class="nf-choice py-2 {{ $amount === $defaultAmount ? 'is-selected' : '' }}">£{{ $amount }}</button>
        @endforeach
        <button type="button" data-choice data-value="other" class="nf-choice py-2">£Other</button>
        <input type="hidden" name="amount" data-choice-input data-amount-input value="{{ $defaultAmount }}">
    </div>

    {{-- Custom amount (revealed when "Other" is chosen) --}}
    <div data-custom-amount class="mt-3 hidden">
        <label class="mb-1.5 block text-sm font-semibold text-navy-dark">Enter your amount</label>
        <input type="number" min="1" step="0.01" placeholder="e.g. 75" data-custom-amount-input
               class="h-11 w-full rounded-md border border-gray-300 px-3 text-sm text-navy-dark focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30">
    </div>

    {{-- Donate --}}
    <button type="submit" class="btn-navy mt-5 w-full py-3">
        Donate Now
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>

    @include('partials.payment-icons')
</form>
