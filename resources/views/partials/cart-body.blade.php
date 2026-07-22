{{-- Unified basket: donations + shop items in one panel. Re-rendered over AJAX after add/remove. --}}
@php
    $cartItems = \App\Support\DonationCart::items();
    $cartSubtotal = \App\Support\DonationCart::subtotal();
    $bagItems = \App\Support\ProductCart::items();
    $bagSubtotal = \App\Support\ProductCart::subtotal();
@endphp

@if (! $cartItems && ! $bagItems)
    <div class="px-5 py-9 text-center">
        <span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-cream text-brand">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L20 8H6" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="10" cy="20" r="1"/><circle cx="17" cy="20" r="1"/>
            </svg>
        </span>
        <p class="mt-3 text-sm font-semibold text-navy-dark">Your basket is empty</p>
        <p class="mt-1 text-xs text-gray-500">Choose a cause to give, or browse the shop.</p>
        <div class="mt-4 grid grid-cols-2 gap-2">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-md border border-gray-200 px-3 py-2 text-sm font-semibold text-navy transition hover:bg-gray-50">Causes</a>
            <a href="{{ route('shop') }}" class="btn-brand justify-center py-2">Shop</a>
        </div>
    </div>
@else
    <div class="max-h-[24rem] overflow-y-auto">
        {{-- ===== Donations ===== --}}
        @if ($cartItems)
            <div class="flex items-center gap-2 px-4 pt-3 pb-1">
                <span class="text-[11px] font-bold uppercase tracking-wide text-brand">Donations</span>
                <span class="h-px flex-1 bg-gray-100"></span>
            </div>
            <ul class="divide-y divide-gray-100">
                @foreach ($cartItems as $item)
                    <li class="flex items-center gap-3 px-4 py-3">
                        <img src="{{ asset($item['image']) }}" alt="" class="h-12 w-12 shrink-0 rounded-md object-cover">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-navy-dark">{{ $item['cause'] }}</p>
                            <p class="text-xs text-gray-500">
                                {{ money($item['amount']) }} each
                                @if (($item['frequency'] ?? 'one-off') === 'monthly')
                                    <span class="font-semibold text-brand">/ monthly</span>
                                @endif
                            </p>
                            <div class="mt-1.5 flex items-center justify-between gap-2">
                                @include('partials.cart-stepper', ['item' => $item])
                                <span class="text-sm font-bold text-navy-dark">{{ money($item['amount'] * $item['qty']) }}</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('donate.remove', $item['id']) }}" data-cart-remove class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Remove {{ $item['cause'] }}"
                                    class="grid h-7 w-7 place-items-center rounded-full text-gray-400 transition-colors hover:bg-brand/10 hover:text-brand">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
            <div class="px-4 py-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-semibold text-gray-600">Donations subtotal</span>
                    <span class="text-base font-extrabold text-navy-dark">{{ money($cartSubtotal) }}</span>
                </div>
                <a href="{{ route('donate.checkout') }}" class="btn-brand mt-2.5 w-full justify-center py-2.5">
                    Complete Donation
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        @endif

        {{-- ===== Shop items ===== --}}
        @if ($bagItems)
            <div class="flex items-center gap-2 px-4 pt-3 pb-1 {{ $cartItems ? 'mt-1 border-t border-gray-100' : '' }}">
                <span class="text-[11px] font-bold uppercase tracking-wide text-navy">Shop items</span>
                <span class="h-px flex-1 bg-gray-100"></span>
            </div>
            <ul class="divide-y divide-gray-100">
                @foreach ($bagItems as $item)
                    @php $p = $item['product']; @endphp
                    <li class="flex items-center gap-3 px-4 py-3">
                        <img src="{{ asset($p->image) }}" alt="" class="h-12 w-12 shrink-0 rounded-md object-cover">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-navy-dark">{{ $p->name }}</p>
                            <p class="text-xs text-gray-500">{{ money($item['unit']) }} &times; {{ $item['qty'] }}</p>
                            <p class="mt-0.5 text-sm font-bold text-navy-dark">{{ money($item['line']) }}</p>
                        </div>
                        <form method="POST" action="{{ route('shop.cart.remove', $item['id']) }}" data-cart-remove class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Remove {{ $p->name }}"
                                    class="grid h-7 w-7 place-items-center rounded-full text-gray-400 transition-colors hover:bg-brand/10 hover:text-brand">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
            <div class="px-4 py-3">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-semibold text-gray-600">Shop subtotal</span>
                    <span class="text-base font-extrabold text-navy-dark">{{ money($bagSubtotal) }}</span>
                </div>
                <div class="mt-2.5 grid grid-cols-2 gap-2">
                    <a href="{{ route('shop.cart') }}" class="inline-flex items-center justify-center rounded-md border border-gray-200 px-3 py-2.5 text-sm font-semibold text-navy transition hover:bg-gray-50">View bag</a>
                    <a href="{{ route('shop.checkout') }}" class="btn-brand justify-center py-2.5">Checkout</a>
                </div>
            </div>
        @endif
    </div>
@endif
