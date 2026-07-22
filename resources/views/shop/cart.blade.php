@extends('layouts.app')

@section('title', 'Your Bag — ' . config('app.name'))
@section('header-solid', 'yes')

@section('content')

    <section class="bg-cream/40 py-10 sm:py-14">
        <div class="nf-container">
            <h1 class="text-3xl font-extrabold text-navy-dark sm:text-4xl">Your bag</h1>

            @if (empty($items))
                <div class="mt-8 rounded-2xl border border-dashed border-gray-300 bg-white p-14 text-center">
                    <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" stroke-linejoin="round"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round"/></svg>
                    </span>
                    <h3 class="mt-4 text-lg font-semibold text-navy-dark">Your bag is empty</h3>
                    <p class="mt-1 text-sm text-gray-500">Browse the shop and add some items to get started.</p>
                    <a href="{{ route('shop') }}" class="btn-brand mt-5">Browse the shop</a>
                </div>
            @else
                <div class="mt-8 grid gap-8 lg:grid-cols-3 lg:items-start">
                    {{-- Items --}}
                    <div class="lg:col-span-2">
                        <div class="divide-y divide-gray-100 overflow-hidden rounded-2xl border border-navy/10 bg-white shadow-sm">
                            @foreach ($items as $item)
                                @php $p = $item['product']; @endphp
                                <div class="flex items-center gap-4 p-4 sm:p-5">
                                    <a href="{{ route('shop.show', $p) }}" class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-cream">
                                        <img src="{{ asset($p->image) }}" alt="{{ $p->name }}" class="h-full w-full object-cover">
                                    </a>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[11px] font-semibold uppercase tracking-wide text-brand">{{ $p->category }}</span>
                                        <h3 class="truncate font-bold text-navy-dark"><a href="{{ route('shop.show', $p) }}" class="hover:text-brand">{{ $p->name }}</a></h3>
                                        <p class="text-sm text-gray-500">£{{ number_format($p->price, 2) }} each</p>

                                        <div class="mt-2 flex items-center gap-3">
                                            <form method="POST" action="{{ route('shop.cart.update', $item['id']) }}" class="inline-flex h-9 items-center rounded-lg border border-gray-200">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" name="qty" value="{{ $item['qty'] - 1 }}" class="grid h-full w-8 place-items-center text-gray-500 transition hover:text-brand" aria-label="Decrease">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14" stroke-linecap="round"/></svg>
                                                </button>
                                                <span class="grid h-full w-8 place-items-center text-sm font-bold text-navy-dark">{{ $item['qty'] }}</span>
                                                <button type="submit" name="qty" value="{{ $item['qty'] + 1 }}" class="grid h-full w-8 place-items-center text-gray-500 transition hover:text-brand" aria-label="Increase">
                                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('shop.cart.remove', $item['id']) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-semibold text-gray-400 transition hover:text-red-600">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="shrink-0 text-right font-extrabold text-navy-dark">£{{ number_format($item['line'], 2) }}</div>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ route('shop') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-navy transition hover:text-brand">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M11 18l-6-6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Continue shopping
                        </a>
                    </div>

                    {{-- Summary --}}
                    <div class="lg:sticky lg:top-28 lg:col-span-1">
                        <div class="rounded-2xl border border-navy/10 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-bold text-navy-dark">Order summary</h2>
                            <div class="mt-4 space-y-3 text-sm">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span class="font-semibold text-navy-dark">£{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Delivery</span>
                                    <span class="font-semibold text-navy-dark">Calculated at checkout</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-100 pt-3 text-base">
                                    <span class="font-bold text-navy-dark">Total</span>
                                    <span class="font-extrabold text-navy-dark">£{{ number_format($subtotal, 2) }}</span>
                                </div>
                            </div>
                            <a href="{{ route('shop.checkout') }}" class="btn-brand mt-5 w-full justify-center py-3 text-base">
                                Proceed to checkout
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </a>
                            <p class="mt-3 text-center text-xs text-gray-400">Every purchase supports Naeem Foundation&rsquo;s work.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
