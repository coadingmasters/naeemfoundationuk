{{-- Inline "choose a cause" dropdown for the hero donation forms.
     Submits the chosen cause as name="cause".

     The picker only appears on Projects & Appeals pages. There it offers just the
     four flexible Islamic giving types (Sadaqah, Lillah, Sadaqah Jariyah, Zakat) —
     every other cause has its own page reachable from the header menu, so the
     list isn't repeated here. On Islamic Giving & Ramadan pages the cause is
     fixed, so the dropdown is hidden and the page's own cause is submitted through
     a hidden field instead.

     Params:
       $selectedCause (string) — the cause pre-selected / fixed for this page.
       $showCause     (bool, optional) — force the picker on/off; auto-detected
                                         from the current Giving section otherwise.
--}}
@php
    $selectedCause = $selectedCause ?? 'Where Most Needed';

    $giving = config('giving', []);

    // Decide whether the donor may switch cause. Explicit $showCause wins; else
    // only pages under the Projects / Appeals menu sections get the picker.
    if (! isset($showCause)) {
        $pickerRoutes = [];
        foreach (['projects', 'appeals'] as $section) {
            foreach ($giving[$section]['items'] ?? [] as $item) {
                if (! empty($item['route'])) {
                    $pickerRoutes[] = $item['route'];
                }
            }
        }
        $currentRoute = optional(request()->route())->getName();
        $showCause = in_array($currentRoute, $pickerRoutes, true) || request()->routeIs('appeals.*');
    }

    // The only four options shown in the Projects / Appeals picker.
    $causeOptions = ['Sadaqah', 'Lillah', 'Sadaqah Jariyah', 'Zakat'];

    // When the picker is shown, the page's own cause (e.g. "Clean Water") isn't
    // one of the four, so default the selection to the first option instead.
    if ($showCause && ! in_array($selectedCause, $causeOptions, true)) {
        $selectedCause = $causeOptions[0];
    }
@endphp

@if ($showCause)
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
@else
    {{-- Fixed-cause page: submit this page's cause without a picker. --}}
    <input type="hidden" name="cause" value="{{ $selectedCause }}">
@endif
