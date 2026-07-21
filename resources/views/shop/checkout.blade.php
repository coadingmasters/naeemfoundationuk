@extends('layouts.app')

@section('title', 'Checkout — ' . config('app.name'))
@section('header-solid', 'yes')

@section('content')

    <section class="bg-cream/40 py-10 sm:py-14">
        <div class="nf-container">
            <nav class="mb-6 flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('shop.cart') }}" class="transition hover:text-brand">Bag</a>
                <span aria-hidden="true">&rsaquo;</span>
                <span class="text-navy-dark">Checkout</span>
            </nav>

            <h1 class="text-3xl font-extrabold text-navy-dark sm:text-4xl">Checkout</h1>

            <form method="POST" action="{{ route('shop.checkout.place') }}" class="mt-8 grid gap-8 lg:grid-cols-3 lg:items-start">
                @csrf

                {{-- Details --}}
                <div class="lg:col-span-2">
                    <div class="nf-reveal rounded-2xl border border-navy/10 bg-white p-6 shadow-sm sm:p-8">
                        <h2 class="text-xl font-bold text-navy-dark">Delivery details</h2>

                        @if ($errors->any())
                            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                                Please check the highlighted fields and try again.
                            </div>
                        @endif

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <input name="name" type="text" value="{{ old('name') }}" required placeholder="Full name *"
                                   class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/25 @error('name') border-red-300 @enderror">
                            <input name="email" type="email" value="{{ old('email') }}" required placeholder="Email address *"
                                   class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/25 @error('email') border-red-300 @enderror">
                        </div>
                        <div class="mt-4">
                            <input name="phone" type="tel" value="{{ old('phone') }}" required placeholder="Phone number *"
                                   class="h-12 w-full rounded-lg border border-gray-200 bg-white px-4 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/25 @error('phone') border-red-300 @enderror">
                        </div>
                        <div class="mt-4">
                            <textarea name="address" rows="3" required placeholder="Delivery address *"
                                      class="w-full rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/25 @error('address') border-red-300 @enderror">{{ old('address') }}</textarea>
                        </div>

                        <div class="mt-6 rounded-xl bg-cream/70 p-4 text-sm text-gray-600 ring-1 ring-navy/10">
                            <p class="flex items-center gap-2 font-semibold text-navy-dark">
                                <svg class="h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                                Card payment
                            </p>
                            <p class="mt-1 text-xs">Place your order now and our team will confirm the total (incl. delivery) and take secure payment by phone or a payment link.</p>
                        </div>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="lg:sticky lg:top-28 lg:col-span-1">
                    <div class="nf-reveal rounded-2xl border border-navy/10 bg-white p-6 shadow-sm" data-reveal-delay="120">
                        <h2 class="text-lg font-bold text-navy-dark">Your order</h2>
                        <div class="mt-4 space-y-3">
                            @foreach ($items as $item)
                                @php $p = $item['product']; @endphp
                                <div class="flex items-center gap-3">
                                    <span class="relative h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-cream">
                                        <img src="{{ asset($p->image) }}" alt="" class="h-full w-full object-cover">
                                        <span class="absolute -right-1 -top-1 grid h-5 w-5 place-items-center rounded-full bg-navy text-[10px] font-bold text-white">{{ $item['qty'] }}</span>
                                    </span>
                                    <span class="min-w-0 flex-1 truncate text-sm text-navy-dark">{{ $p->name }}</span>
                                    <span class="text-sm font-semibold text-navy-dark">£{{ number_format($item['line'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-between border-t border-gray-100 pt-4 text-base">
                            <span class="font-bold text-navy-dark">Total</span>
                            <span class="font-extrabold text-navy-dark">£{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <button type="submit" class="btn-brand mt-5 w-full justify-center py-3 text-base">
                            Place order
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection
