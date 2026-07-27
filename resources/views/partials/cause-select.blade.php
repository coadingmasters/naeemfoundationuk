{{-- Inline "choose a cause" dropdown for the hero donation forms.
     Submits the chosen cause as name="cause". The option list always mirrors the
     Giving mega-menu (Appeals + Islamic Giving), so it never drifts out of sync.

     Param: $selectedCause (string) — the cause pre-selected for this page.
--}}
@php
    $selectedCause = $selectedCause ?? 'Where Most Needed';

    $giving = config('giving', []);
    $causeOptions = ['Where Most Needed'];
    foreach (['appeals', 'islamic'] as $section) {
        foreach ($giving[$section]['items'] ?? [] as $item) {
            $causeOptions[] = $item['title'];
        }
    }
    $causeOptions = array_values(array_unique($causeOptions));

    // Keep a page-specific cause selectable even if it isn't a menu item.
    if (! in_array($selectedCause, $causeOptions, true)) {
        array_unshift($causeOptions, $selectedCause);
    }
@endphp

<div class="mt-4 text-left">
    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-navy">Choose a cause</label>
    <div class="nf-cselect h-12 rounded-lg border border-navy/20 bg-white" data-cselect>
        <button type="button" class="nf-cselect__btn" data-cselect-btn aria-haspopup="listbox" aria-expanded="false">
            <span data-cselect-label>{{ $selectedCause }}</span>
            <svg class="nf-cselect__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <ul class="nf-cselect__menu" role="listbox" data-cselect-menu>
            @foreach ($causeOptions as $option)
                <li class="nf-cselect__opt {{ $option === $selectedCause ? 'is-selected' : '' }}" role="option" data-value="{{ $option }}">{{ $option }}</li>
            @endforeach
        </ul>
        <input type="hidden" name="cause" data-cselect-input value="{{ $selectedCause }}">
    </div>
</div>
