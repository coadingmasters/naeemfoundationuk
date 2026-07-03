{{-- Reusable "Donate For Betterment" widget. Optional: $widgetCauses (array). --}}
@php
    $widgetCauses = $widgetCauses ?? ['Where Most Needed', 'Sadaqah', 'Zakat', 'Orphan Support'];
@endphp

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-xl shadow-navy/5">
    <div class="bg-gradient-to-br from-navy to-navy-dark px-6 py-4 text-center">
        <h2 class="text-lg font-bold text-white sm:text-xl">Donate For Betterment</h2>
        <p class="mt-0.5 text-xs text-white/70">100% of your donation reaches those in need.</p>
    </div>

    <div class="space-y-4 p-5 sm:p-6">
        {{-- Frequency --}}
        <div class="grid grid-cols-2 gap-3" data-choice-group>
            <button type="button" data-choice class="nf-choice is-selected py-2">One-Off</button>
            <button type="button" data-choice class="nf-choice py-2">Monthly</button>
        </div>

        {{-- Currency --}}
        <div>
            <label class="mb-1.5 block text-sm font-semibold text-navy-dark">Select your currency</label>
            <div class="nf-cselect h-11 rounded-md border border-gray-300" data-cselect>
                <button type="button" class="nf-cselect__btn" data-cselect-btn aria-haspopup="listbox" aria-expanded="false">
                    <span data-cselect-label>GBP £</span>
                    <svg class="nf-cselect__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <ul class="nf-cselect__menu" role="listbox" data-cselect-menu>
                    <li class="nf-cselect__opt is-selected" role="option" data-value="GBP">GBP £</li>
                    <li class="nf-cselect__opt" role="option" data-value="USD">USD $</li>
                    <li class="nf-cselect__opt" role="option" data-value="EUR">EUR €</li>
                    <li class="nf-cselect__opt" role="option" data-value="PKR">PKR ₨</li>
                    <li class="nf-cselect__opt" role="option" data-value="CAD">CAD $</li>
                </ul>
                <input type="hidden" name="currency" data-cselect-input value="GBP">
            </div>
        </div>

        {{-- Amounts --}}
        <div class="grid grid-cols-4 gap-2" data-choice-group>
            <button type="button" data-choice class="nf-choice py-2">£30</button>
            <button type="button" data-choice class="nf-choice is-selected py-2">£50</button>
            <button type="button" data-choice class="nf-choice py-2">£100</button>
            <button type="button" data-choice class="nf-choice py-2">Other</button>
        </div>

        {{-- Cause --}}
        <div class="nf-cselect h-11 rounded-md border border-gray-300" data-cselect>
            <button type="button" class="nf-cselect__btn nf-cselect__btn--placeholder" data-cselect-btn aria-haspopup="listbox" aria-expanded="false">
                <span data-cselect-label>Select a cause</span>
                <svg class="nf-cselect__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <ul class="nf-cselect__menu" role="listbox" data-cselect-menu>
                @foreach ($widgetCauses as $c)
                    <li class="nf-cselect__opt" role="option" data-value="{{ $c }}">{{ $c }}</li>
                @endforeach
            </ul>
            <input type="hidden" name="cause" data-cselect-input value="">
        </div>

        {{-- Gift Aid --}}
        <label class="flex cursor-pointer items-start gap-2.5 rounded-xl bg-brand p-3.5 text-white">
            <input type="checkbox" name="gift_aid" value="1" class="mt-0.5 h-4 w-4 shrink-0 rounded border-white/50 bg-white/20 text-white focus:ring-white/40">
            <span class="text-xs leading-relaxed text-white/95">
                <span class="text-sm font-bold">Gift Aid</span> — I'm a UK taxpayer; add 25% to my donation at no extra cost.
            </span>
        </label>

        <button type="button" class="btn-brand w-full py-2.5">
            Donate Now
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
    </div>
</div>
