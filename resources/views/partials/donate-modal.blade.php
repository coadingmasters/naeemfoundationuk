{{-- Animated "choose a cause" donation popup.
     Opened by the hero widget's Donate button. Lets the donor pick ANY cause
     from the Giving menu, then adds it to the basket.

     Params:
       $cause        (string) cause pre-selected when the popup opens
       $widgetImage  (string) basket thumbnail
--}}
@php
    $modalCause = $cause ?? 'Where Most Needed';
    $modalImage = $widgetImage ?? 'images/changinslives1.jpg';

    // Flatten the Giving mega-menu into one cause list so this dropdown always
    // matches the header — Appeals first, then Islamic Giving.
    $giving = config('giving', []);
    $causeOptions = ['Where Most Needed'];
    foreach (['appeals', 'islamic'] as $section) {
        foreach ($giving[$section]['items'] ?? [] as $item) {
            $causeOptions[] = $item['title'];
        }
    }
    $causeOptions = array_values(array_unique($causeOptions));

    // If the page's cause isn't in the menu list, still make it selectable.
    if (! in_array($modalCause, $causeOptions, true)) {
        array_unshift($causeOptions, $modalCause);
    }

    $modalPresets = [50, 100, 250];
    $modalMonthly = [10, 25, 50];
    $modalDefault = 100;
@endphp

<div class="nf-modal" data-donate-modal hidden>
    <div class="nf-modal__backdrop" data-donate-close></div>
    <div class="nf-modal__card" role="dialog" aria-modal="true" aria-label="Make a donation">
        <button type="button" class="nf-modal__close" data-donate-close aria-label="Close">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
        </button>

        <div class="text-center">
            <span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-brand/10 text-brand">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.8 5.6a5.5 5.5 0 0 0-7.8 0L12 6.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 22l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="mt-3 text-xl font-extrabold text-navy-dark">Make a donation</h3>
            <p class="mt-1 text-sm text-gray-500">Choose a cause and an amount to give.</p>
        </div>

        <form method="POST" action="{{ route('donate.add') }}" data-donate-form data-donate-modal-form class="mt-5">
            @csrf
            <input type="hidden" name="image" value="{{ $modalImage }}">

            {{-- Cause dropdown --}}
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-navy">Choose a cause</label>
            <div class="nf-cselect h-12 rounded-lg border border-navy/20 bg-white" data-cselect>
                <button type="button" class="nf-cselect__btn" data-cselect-btn aria-haspopup="listbox" aria-expanded="false">
                    <span data-cselect-label>{{ $modalCause }}</span>
                    <svg class="nf-cselect__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <ul class="nf-cselect__menu" role="listbox" data-cselect-menu>
                    @foreach ($causeOptions as $option)
                        <li class="nf-cselect__opt {{ $option === $modalCause ? 'is-selected' : '' }}" role="option" data-value="{{ $option }}">{{ $option }}</li>
                    @endforeach
                </ul>
                <input type="hidden" name="cause" data-cselect-input value="{{ $modalCause }}">
            </div>

            {{-- Frequency --}}
            <div class="mt-4 grid grid-cols-2 gap-3" data-choice-group>
                <button type="button" data-choice data-value="one-off" class="nf-choice is-selected py-2.5">One-Off</button>
                <button type="button" data-choice data-value="monthly" class="nf-choice py-2.5">Monthly</button>
                <input type="hidden" name="frequency" data-choice-input value="one-off">
            </div>

            {{-- Amounts --}}
            <div class="mt-3 grid grid-cols-4 gap-2" data-choice-group>
                @foreach ($modalPresets as $i => $amount)
                    <button type="button" data-choice data-value="{{ $amount }}"
                            data-oneoff="{{ $amount }}" data-monthly="{{ $modalMonthly[$i] ?? $amount }}"
                            class="nf-choice py-2 {{ $amount === $modalDefault ? 'is-selected' : '' }}">{{ region('symbol') }}{{ $amount }}</button>
                @endforeach
                <button type="button" data-choice data-value="other" class="nf-choice py-2">Other</button>
                <input type="hidden" name="amount" data-choice-input data-amount-input value="{{ $modalDefault }}">
            </div>

            {{-- Custom amount --}}
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
    </div>
</div>
