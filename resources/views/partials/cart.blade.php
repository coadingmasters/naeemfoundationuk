{{-- Header basket: icon + count badge + dropdown panel. --}}
@php
    $cartCount = \App\Support\DonationCart::count();
@endphp

<div class="nf-cart" data-cart>
    <button type="button" data-cart-toggle class="nf-cart__btn"
            aria-haspopup="dialog" aria-expanded="false" aria-label="Your basket">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L20 8H6" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="10" cy="20" r="1"/><circle cx="17" cy="20" r="1"/>
        </svg>
        <span class="nf-cart__badge {{ $cartCount ? '' : 'hidden' }}" data-cart-count>{{ $cartCount }}</span>
    </button>

    <div class="nf-cart__panel" data-cart-panel role="dialog" aria-label="Your basket">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
            <p class="text-sm font-extrabold text-navy-dark">Your Basket</p>
            <button type="button" data-cart-close aria-label="Close basket"
                    class="grid h-7 w-7 place-items-center rounded-full text-gray-400 transition-colors hover:bg-gray-100 hover:text-navy-dark">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <div data-cart-body>
            @include('partials.cart-body')
        </div>
    </div>
</div>
