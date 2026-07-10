{{-- Basket contents. Rendered on page load and re-rendered over AJAX after add/remove. --}}
@php
    $cartItems = \App\Support\DonationCart::items();
    $cartSubtotal = \App\Support\DonationCart::subtotal();
@endphp

@if (! $cartItems)
    <div class="px-5 py-9 text-center">
        <span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-cream text-brand">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L20 8H6" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="10" cy="20" r="1"/><circle cx="17" cy="20" r="1"/>
            </svg>
        </span>
        <p class="mt-3 text-sm font-semibold text-navy-dark">Your basket is empty</p>
        <p class="mt-1 text-xs text-gray-500">Choose a cause and start giving.</p>
        <a href="{{ route('home') }}" class="btn-brand mt-4 w-full justify-center py-2">Browse causes</a>
    </div>
@else
    <ul class="max-h-72 divide-y divide-gray-100 overflow-y-auto">
        @foreach ($cartItems as $item)
            <li class="flex items-center gap-3 px-4 py-3">
                <img src="{{ asset($item['image']) }}" alt="" class="h-12 w-12 shrink-0 rounded-md object-cover">

                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-navy-dark">{{ $item['cause'] }}</p>
                    <p class="text-xs text-gray-500">
                        £{{ number_format($item['amount'], 2) }} each
                        @if (($item['frequency'] ?? 'one-off') === 'monthly')
                            <span class="font-semibold text-brand">/ monthly</span>
                        @endif
                    </p>
                    <div class="mt-1.5 flex items-center justify-between gap-2">
                        @include('partials.cart-stepper', ['item' => $item])
                        <span class="text-sm font-bold text-navy-dark">
                            £{{ number_format($item['amount'] * $item['qty'], 2) }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('donate.remove', $item['id']) }}" data-cart-remove class="shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" aria-label="Remove {{ $item['cause'] }}"
                            class="grid h-7 w-7 place-items-center rounded-full text-gray-400 transition-colors hover:bg-brand/10 hover:text-brand">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/>
                        </svg>
                    </button>
                </form>
            </li>
        @endforeach
    </ul>

    <div class="border-t border-gray-100 px-4 py-4">
        <div class="flex items-center justify-between text-sm">
            <span class="font-semibold text-gray-600">Subtotal</span>
            <span class="text-base font-extrabold text-navy-dark">£{{ number_format($cartSubtotal, 2) }}</span>
        </div>

        <a href="{{ route('donate.checkout') }}" class="btn-brand mt-3 w-full justify-center py-2.5">
            Complete Donation
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
@endif
