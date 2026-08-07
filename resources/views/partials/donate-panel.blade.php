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
       $image         (string)  basket thumbnail --}}
@php
    $causes = $causes ?? ['Where Most Needed'];
    $oneOffAmounts = array_slice(array_values($oneOffAmounts ?? [20, 50, 100]), 0, 3);
    $monthlyDefault = $monthlyDefault ?? 30;
    $yearlyDefault = $yearlyDefault ?? ($monthlyDefault * 12);
    $orphanId = $orphanId ?? null;
    $image = $image ?? 'images/changinslives1.jpg';
    $panelTitle = $panelTitle ?? 'Make a Donation';
    $defaultAmount = $oneOffAmounts[1] ?? $oneOffAmounts[0];
    $sym = region('symbol');
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
        <button type="button" data-freq="one-off" class="nf-choice is-selected py-2.5">One-Off</button>
        <button type="button" data-freq="monthly" class="nf-choice py-2.5">Monthly</button>
        <button type="button" data-freq="yearly" class="nf-choice py-2.5">Yearly</button>
        <input type="hidden" name="frequency" data-freq-input value="one-off">
    </div>

    {{-- Currency selector --}}
    <div class="mt-5 flex items-center justify-center gap-3">
        <span class="text-sm font-bold text-brand sm:text-base">Select Your Currency</span>
        <details class="nf-ccy relative">
            <summary class="flex cursor-pointer list-none items-center gap-2 rounded-md bg-brand px-4 py-2 text-sm font-bold text-white">
                {{ region('currency') }} {{ $sym }}
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </summary>
            <div class="absolute right-0 z-30 mt-1 w-40 overflow-hidden rounded-lg bg-white py-1 text-sm shadow-2xl ring-1 ring-black/10">
                @foreach ($regions as $r)
                    <a href="{{ route('region.set', $r['code']) }}"
                       class="flex items-center gap-2 px-3 py-2 text-navy-dark hover:bg-cream {{ $r['code'] === region('code') ? 'font-bold text-brand' : '' }}">
                        <span>{{ $r['flag'] }}</span> {{ $r['currency'] }} {{ $r['symbol'] }}
                    </a>
                @endforeach
            </div>
        </details>
    </div>

    {{-- Amount — one-off boxes --}}
    <div class="mt-4 grid grid-cols-4 gap-2" data-amt-oneoff>
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
    <div class="mt-4 hidden" data-amt-recurring>
        <div class="relative">
            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 font-bold text-brand">{{ $sym }}</span>
            <input type="number" min="1" step="1" inputmode="numeric" data-whole-number data-amt-recurring-input value="{{ $monthlyDefault }}"
                   class="h-12 w-full rounded-lg border border-brand/40 pl-7 pr-3 text-center text-base font-bold text-brand focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25">
        </div>
    </div>

    <input type="hidden" name="amount" data-amt-input value="{{ $defaultAmount }}">

    {{-- Cause dropdown (reuses the site's nf-cselect component + JS) --}}
    <div class="mt-5 text-left">
        <label class="mb-1.5 block text-sm font-bold text-navy-dark">Select Cause</label>
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
    </div>

    {{-- Gift Aid — a UK-only tax relief. --}}
    @if (region('gift_aid'))
        <div class="mt-5 rounded-xl bg-brand p-5 text-white">
            <h4 class="text-base font-bold">Gift Aid</h4>
            <p class="mt-1.5 text-xs leading-relaxed text-white/85">
                As a UK taxpayer, you can increase the impact of your donation by 25% at no extra cost.
                Simply check the Gift Aid box when you donate.
            </p>
            <label class="mt-3 flex cursor-pointer items-start gap-2.5">
                <input type="checkbox" name="gift_aid" value="1"
                       class="mt-0.5 h-5 w-5 shrink-0 rounded border-white/40 bg-white/10 text-white focus:ring-2 focus:ring-white/40">
                <span class="text-xs leading-relaxed text-white/90">Yes, I am a UK taxpayer and want Naeem Foundation to claim Gift Aid on my donation.</span>
            </label>
            <p class="mt-3 text-xs font-semibold text-white/90">Let’s make a bigger difference together.</p>
        </div>
    @endif

    <button type="submit" class="btn-navy mt-5 w-full py-3">
        Donate Now
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>

    @include('partials.payment-icons')
</form>
