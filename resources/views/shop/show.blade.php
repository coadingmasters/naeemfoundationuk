@extends('layouts.app')

@section('title', $product->name . ' — ' . config('app.name'))
@section('header-solid', 'yes')

@section('content')

    <section class="bg-cream/40 py-10 sm:py-14">
        <div class="nf-container">
            {{-- Breadcrumb --}}
            <nav class="mb-6 flex flex-wrap items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="transition hover:text-brand">Home</a>
                <span aria-hidden="true">&rsaquo;</span>
                <a href="{{ route('shop') }}" class="transition hover:text-brand">Shop</a>
                <span aria-hidden="true">&rsaquo;</span>
                <a href="{{ route('shop', ['category' => $product->category]) }}" class="transition hover:text-brand">{{ $product->category }}</a>
                <span aria-hidden="true">&rsaquo;</span>
                <span class="text-navy-dark">{{ \Illuminate\Support\Str::limit($product->name, 30) }}</span>
            </nav>

            <div class="grid gap-8 lg:grid-cols-2 lg:gap-12">
                {{-- Image --}}
                <div class="nf-reveal relative overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-black/5">
                    <div class="aspect-square">
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                    </div>
                    @if ($product->badge)
                        <span class="absolute left-4 top-4 rounded-full bg-brand px-3 py-1 text-xs font-bold uppercase tracking-wide text-white shadow">{{ $product->badge }}</span>
                    @endif
                </div>

                {{-- Details --}}
                <div class="nf-reveal flex flex-col justify-center" data-reveal-delay="80">
                    <span class="text-sm font-semibold uppercase tracking-wide text-brand">{{ $product->category }}</span>
                    <h1 class="mt-2 text-3xl font-extrabold leading-tight text-navy-dark sm:text-4xl">{{ $product->name }}</h1>

                    <div class="mt-4 flex items-center gap-3">
                        <span class="text-3xl font-extrabold text-navy-dark">£{{ number_format($product->price, 2) }}</span>
                        @if ($product->in_stock)
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> In stock
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-500">Sold out</span>
                        @endif
                    </div>

                    <p class="mt-5 text-sm leading-relaxed text-gray-600 sm:text-base">{{ $product->description }}</p>

                    <form method="POST" action="{{ route('shop.cart.add') }}" data-bag-form class="mt-7 flex flex-wrap items-center gap-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="inline-flex h-12 items-center rounded-xl border border-gray-200 bg-white">
                            <button type="button" data-qty-dec class="grid h-full w-11 place-items-center text-gray-500 transition hover:text-brand">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14" stroke-linecap="round"/></svg>
                            </button>
                            <input type="number" name="qty" value="1" min="1" max="20" data-qty-input
                                   class="h-full w-12 border-0 bg-transparent text-center text-sm font-bold text-navy-dark outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                            <button type="button" data-qty-inc class="grid h-full w-11 place-items-center text-gray-500 transition hover:text-brand">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                            </button>
                        </div>

                        <button type="submit" @disabled(! $product->in_stock)
                                class="btn-brand h-12 flex-1 justify-center px-6 text-base disabled:cursor-not-allowed disabled:opacity-40">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" stroke-linejoin="round"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round"/></svg>
                            Add to bag
                        </button>
                    </form>

                    {{-- Trust points --}}
                    <div class="mt-7 grid gap-3 border-t border-navy/10 pt-6 text-sm text-gray-600 sm:grid-cols-3">
                        <span class="inline-flex items-center gap-2"><svg class="h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7.5-4.6-9.5-9A5.2 5.2 0 0 1 12 6.6a5.2 5.2 0 0 1 9.5 5.4c-2 4.4-9.5 9-9.5 9Z"/></svg> Supports our cause</span>
                        <span class="inline-flex items-center gap-2"><svg class="h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg> Secure checkout</span>
                        <span class="inline-flex items-center gap-2"><svg class="h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h13v10H3zM16 10h3l2 3v4h-5" stroke-linejoin="round"/><circle cx="7.5" cy="17.5" r="1.5"/><circle cx="17.5" cy="17.5" r="1.5"/></svg> UK-wide delivery</span>
                    </div>
                </div>
            </div>

            {{-- Related --}}
            @if ($related->isNotEmpty())
                <div class="mt-16">
                    <h2 class="text-2xl font-bold text-navy-dark">You may also like</h2>
                    <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($related as $r)
                            <div class="nf-reveal group overflow-hidden rounded-2xl border border-navy/10 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg" data-reveal-delay="{{ $loop->index * 70 }}">
                                <a href="{{ route('shop.show', $r) }}" class="block aspect-[4/3] overflow-hidden bg-cream">
                                    <img src="{{ asset($r->image) }}" alt="{{ $r->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                </a>
                                <div class="p-4">
                                    <h3 class="truncate font-bold text-navy-dark"><a href="{{ route('shop.show', $r) }}" class="hover:text-brand">{{ $r->name }}</a></h3>
                                    <p class="mt-1 font-extrabold text-navy-dark">£{{ number_format($r->price, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection
