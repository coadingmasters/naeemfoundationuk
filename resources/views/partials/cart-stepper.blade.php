{{-- Quantity stepper for one basket line. Params: $item, optional $dark.
     Each button is its own form, so it works with JavaScript disabled. --}}
@php
    $dark = $dark ?? false;
    $btn = $dark
        ? 'border-white/40 text-white hover:bg-white/10 disabled:opacity-40'
        : 'border-gray-200 text-navy-dark hover:border-brand hover:text-brand disabled:opacity-40';
    $qtyText = $dark ? 'text-white' : 'text-navy-dark';
@endphp

<div class="inline-flex items-center gap-1.5">
    {{-- Decrease (removes the line at zero) --}}
    <form method="POST" action="{{ route('donate.quantity', $item['id']) }}" data-cart-qty>
        @csrf
        @method('PATCH')
        <input type="hidden" name="qty" value="{{ $item['qty'] - 1 }}">
        <button type="submit" aria-label="Decrease quantity of {{ $item['cause'] }}"
                class="grid h-7 w-7 place-items-center rounded-md border transition-colors {{ $btn }}">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M5 12h14" stroke-linecap="round"/>
            </svg>
        </button>
    </form>

    <span class="w-6 text-center text-sm font-bold {{ $qtyText }}" data-cart-qty-value>{{ $item['qty'] }}</span>

    {{-- Increase --}}
    <form method="POST" action="{{ route('donate.quantity', $item['id']) }}" data-cart-qty>
        @csrf
        @method('PATCH')
        <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">
        <button type="submit" aria-label="Increase quantity of {{ $item['cause'] }}"
                @disabled($item['qty'] >= \App\Support\DonationCart::MAX_QTY)
                class="grid h-7 w-7 place-items-center rounded-md border transition-colors {{ $btn }}">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 5v14M5 12h14" stroke-linecap="round"/>
            </svg>
        </button>
    </form>
</div>
