{{-- Bag contents. Rendered on load and re-rendered over AJAX after add/remove. --}}
@php
    $bagItems = \App\Support\ProductCart::items();
    $bagSubtotal = \App\Support\ProductCart::subtotal();
@endphp

@if (! $bagItems)
    <div class="px-5 py-9 text-center">
        <span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-cream text-brand">
            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" stroke-linejoin="round"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round"/></svg>
        </span>
        <p class="mt-3 text-sm font-semibold text-navy-dark">Your bag is empty</p>
        <p class="mt-1 text-xs text-gray-500">Browse the shop and start adding items.</p>
        <a href="{{ route('shop') }}" class="btn-brand mt-4 w-full justify-center py-2">Browse the shop</a>
    </div>
@else
    <ul class="max-h-72 divide-y divide-gray-100 overflow-y-auto">
        @foreach ($bagItems as $item)
            @php $p = $item['product']; @endphp
            <li class="flex items-center gap-3 px-4 py-3">
                <img src="{{ asset($p->image) }}" alt="" class="h-12 w-12 shrink-0 rounded-md object-cover">
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-navy-dark">{{ $p->name }}</p>
                    <p class="text-xs text-gray-500">£{{ number_format($p->price, 2) }} &times; {{ $item['qty'] }}</p>
                    <p class="mt-0.5 text-sm font-bold text-navy-dark">£{{ number_format($item['line'], 2) }}</p>
                </div>
                <form method="POST" action="{{ route('shop.cart.remove', $item['id']) }}" data-shopbag-remove class="shrink-0">
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

    <div class="border-t border-gray-100 px-4 py-4">
        <div class="flex items-center justify-between text-sm">
            <span class="font-semibold text-gray-600">Subtotal</span>
            <span class="text-base font-extrabold text-navy-dark">£{{ number_format($bagSubtotal, 2) }}</span>
        </div>
        <div class="mt-3 grid grid-cols-2 gap-2">
            <a href="{{ route('shop.cart') }}" class="inline-flex items-center justify-center rounded-md border border-gray-200 px-3 py-2.5 text-sm font-semibold text-navy transition hover:bg-gray-50">View bag</a>
            <a href="{{ route('shop.checkout') }}" class="btn-brand justify-center py-2.5">Checkout</a>
        </div>
    </div>
@endif
