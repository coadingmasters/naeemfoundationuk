{{-- Header shop bag: icon + count badge + dropdown panel (mirrors the donation basket). --}}
@php
    $bagCount = \App\Support\ProductCart::count();
@endphp

<div class="nf-cart" data-shopbag>
    <button type="button" data-shopbag-toggle class="nf-cart__btn"
            aria-haspopup="dialog" aria-expanded="false" aria-label="Your bag">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" stroke-linejoin="round"/>
            <path d="M3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round"/>
        </svg>
        <span class="nf-cart__badge {{ $bagCount ? '' : 'hidden' }}" data-shopbag-count>{{ $bagCount }}</span>
    </button>

    <div class="nf-cart__panel" data-shopbag-panel role="dialog" aria-label="Your bag">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
            <p class="text-sm font-extrabold text-navy-dark">Your Bag</p>
            <button type="button" data-shopbag-close aria-label="Close bag"
                    class="grid h-7 w-7 place-items-center rounded-full text-gray-400 transition-colors hover:bg-gray-100 hover:text-navy-dark">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
            </button>
        </div>

        <div data-shopbag-body>
            @include('partials.shop-bag-body')
        </div>
    </div>
</div>
