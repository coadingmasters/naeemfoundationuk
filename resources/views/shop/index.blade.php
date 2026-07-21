@extends('layouts.app')

@section('title', 'Shop — ' . config('app.name'))
@section('header-solid', 'yes')

@section('content')
@php
    $cur = [
        'q' => request('q'),
        'category' => request('category'),
        'min' => request('min'),
        'max' => request('max'),
        'stock' => request('stock'),
        'sort' => request('sort'),
    ];
@endphp

    {{-- ===================== HERO BAND ===================== --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-navy-dark via-navy to-brand/80 py-12 sm:py-16">
        <div class="pointer-events-none absolute -right-24 -top-10 h-72 w-72 rounded-full bg-brand/30 blur-3xl"></div>
        <div class="nf-container relative text-center text-white">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider ring-1 ring-white/20">
                <span class="h-1.5 w-1.5 rounded-full bg-white"></span> Naeem Foundation Shop
            </span>
            <h1 class="mt-4 text-3xl font-extrabold sm:text-4xl lg:text-5xl">The Shop</h1>
            <p class="mx-auto mt-3 max-w-xl text-sm text-white/80 sm:text-base">
                Every purchase supports our mission. Browse gifts, books, clothing and Ramadan essentials.
            </p>
            <nav class="mt-4 flex items-center justify-center gap-2 text-sm text-white/70">
                <a href="{{ route('home') }}" class="transition hover:text-white">Home</a>
                <span aria-hidden="true">&rsaquo;</span>
                <span class="text-white">Shop</span>
            </nav>
        </div>
    </section>

    {{-- ===================== SHOP GRID + FILTERS ===================== --}}
    <section class="bg-cream/50 py-10 sm:py-12">
        <div class="nf-container grid gap-8 lg:grid-cols-4 lg:items-start">

            {{-- ===== Filters sidebar ===== --}}
            <form method="GET" action="{{ route('shop') }}" id="shopFilters"
                  class="lg:sticky lg:top-28 lg:col-span-1">
                <div class="rounded-2xl border border-navy/10 bg-white p-5 shadow-sm">
                    <h2 class="flex items-center gap-2 text-sm font-bold uppercase tracking-wide text-navy-dark">
                        <svg class="h-4 w-4 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M7 12h10M10 18h4" stroke-linecap="round"/></svg>
                        Filters
                    </h2>

                    {{-- Search --}}
                    <div class="mt-4">
                        <label class="mb-1.5 block text-xs font-semibold text-gray-500">Search</label>
                        <div class="relative">
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4" stroke-linecap="round"/></svg>
                            <input type="search" name="q" value="{{ $cur['q'] }}" placeholder="Search products…"
                                   class="h-10 w-full rounded-lg border border-gray-200 bg-white pl-9 pr-3 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/25">
                        </div>
                    </div>

                    {{-- Categories --}}
                    <div class="mt-5">
                        <p class="mb-2 text-xs font-semibold text-gray-500">Category</p>
                        <div class="space-y-1">
                            <label class="nf-filter-opt">
                                <input type="radio" name="category" value="" @checked(! $cur['category']) onchange="this.form.submit()" class="peer sr-only">
                                <span class="nf-filter-opt__dot"></span>
                                <span class="flex-1">All products</span>
                            </label>
                            @foreach ($categories as $category)
                                <label class="nf-filter-opt">
                                    <input type="radio" name="category" value="{{ $category }}" @checked($cur['category'] === $category) onchange="this.form.submit()" class="peer sr-only">
                                    <span class="nf-filter-opt__dot"></span>
                                    <span class="flex-1">{{ $category }}</span>
                                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-semibold text-gray-500">{{ $counts[$category] ?? 0 }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="mt-5">
                        <p class="mb-2 text-xs font-semibold text-gray-500">Price (£)</p>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min" value="{{ $cur['min'] }}" min="0" max="{{ $maxPrice }}" placeholder="Min"
                                   class="h-10 w-full rounded-lg border border-gray-200 px-3 text-sm text-navy-dark outline-none focus:border-brand focus:ring-2 focus:ring-brand/25">
                            <span class="text-gray-400">–</span>
                            <input type="number" name="max" value="{{ $cur['max'] }}" min="0" max="{{ $maxPrice }}" placeholder="Max"
                                   class="h-10 w-full rounded-lg border border-gray-200 px-3 text-sm text-navy-dark outline-none focus:border-brand focus:ring-2 focus:ring-brand/25">
                        </div>
                    </div>

                    {{-- In stock --}}
                    <label class="mt-5 flex cursor-pointer items-center gap-2.5 text-sm text-navy-dark">
                        <input type="checkbox" name="stock" value="in" @checked($cur['stock'] === 'in') onchange="this.form.submit()"
                               class="h-4 w-4 rounded border-gray-300 text-brand focus:ring-2 focus:ring-brand/30">
                        In stock only
                    </label>

                    {{-- keep sort when applying --}}
                    <input type="hidden" name="sort" value="{{ $cur['sort'] }}">

                    <div class="mt-6 flex gap-2">
                        <button type="submit" class="btn-brand flex-1 justify-center py-2.5 text-sm">Apply</button>
                        <a href="{{ route('shop') }}" class="inline-flex items-center rounded-md border border-gray-200 px-3 py-2.5 text-sm font-semibold text-navy transition hover:bg-gray-50">Clear</a>
                    </div>
                </div>
            </form>

            {{-- ===== Products ===== --}}
            <div class="lg:col-span-3">
                {{-- Toolbar --}}
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-sm text-gray-500">
                        Showing <span class="font-semibold text-navy-dark">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</span>
                        of <span class="font-semibold text-navy-dark">{{ $products->total() }}</span> products
                    </p>
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 text-sm">
                            <span class="text-gray-500">Sort</span>
                            <select name="sort" form="shopFilters" onchange="document.getElementById('shopFilters').submit()"
                                    class="h-10 rounded-lg border border-gray-200 bg-white px-3 text-sm font-medium text-navy-dark outline-none focus:border-brand focus:ring-2 focus:ring-brand/25">
                                <option value="" @selected(! $cur['sort'])>Featured</option>
                                <option value="newest" @selected($cur['sort'] === 'newest')>Newest</option>
                                <option value="price_asc" @selected($cur['sort'] === 'price_asc')>Price: low to high</option>
                                <option value="price_desc" @selected($cur['sort'] === 'price_desc')>Price: high to low</option>
                                <option value="name" @selected($cur['sort'] === 'name')>Name: A–Z</option>
                            </select>
                        </label>
                        @php $bagCount = \App\Support\ProductCart::count(); @endphp
                        <a href="{{ route('shop.cart') }}" class="inline-flex h-10 items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 text-sm font-semibold text-navy transition hover:border-brand hover:text-brand">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" stroke-linejoin="round"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round"/></svg>
                            <span class="hidden sm:inline">Bag</span>
                            <span class="grid h-5 min-w-[1.25rem] place-items-center rounded-full bg-brand px-1 text-[11px] font-bold text-white {{ $bagCount ? '' : 'hidden' }}" data-shopbag-count>{{ $bagCount }}</span>
                        </a>
                    </div>
                </div>

                @if ($products->isEmpty())
                    <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-14 text-center">
                        <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" stroke-linejoin="round"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round"/></svg>
                        </span>
                        <h3 class="mt-4 text-lg font-semibold text-navy-dark">No products found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or clearing your search.</p>
                        <a href="{{ route('shop') }}" class="btn-brand mt-5">Clear filters</a>
                    </div>
                @else
                    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($products as $product)
                            <div class="nf-reveal group flex flex-col overflow-hidden rounded-2xl border border-navy/10 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl" data-reveal-delay="{{ ($loop->index % 3) * 70 }}">
                                <a href="{{ route('shop.show', $product) }}" class="relative block aspect-[4/3] overflow-hidden bg-cream">
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @if ($product->badge)
                                        <span class="absolute left-3 top-3 rounded-full bg-brand px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-white shadow">{{ $product->badge }}</span>
                                    @endif
                                    @unless ($product->in_stock)
                                        <span class="absolute inset-0 grid place-items-center bg-white/70 text-sm font-bold uppercase tracking-wide text-navy-dark">Sold out</span>
                                    @endunless
                                </a>
                                <div class="flex flex-1 flex-col p-4">
                                    <span class="text-[11px] font-semibold uppercase tracking-wide text-brand">{{ $product->category }}</span>
                                    <h3 class="mt-1 font-bold leading-snug text-navy-dark">
                                        <a href="{{ route('shop.show', $product) }}" class="transition-colors hover:text-brand">{{ $product->name }}</a>
                                    </h3>
                                    <div class="mt-2 flex items-baseline gap-2">
                                        <span class="text-lg font-extrabold text-navy-dark">£{{ number_format($product->price, 2) }}</span>
                                        @unless ($product->in_stock)
                                            <span class="text-xs font-semibold text-gray-400">&middot; Sold out</span>
                                        @endunless
                                    </div>

                                    <form method="POST" action="{{ route('shop.cart.add') }}" data-bag-form class="mt-auto pt-4">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" @disabled(! $product->in_stock) class="nf-addcart">
                                            <svg class="nf-addcart__icon h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="18" cy="21" r="1"/><path d="M3 3h2l2.4 11.5a2 2 0 0 0 2 1.6h8.2a2 2 0 0 0 2-1.5L22 7H6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($products->hasPages())
                        <div class="mt-8">{{ $products->withQueryString()->links() }}</div>
                    @endif
                @endif
            </div>
        </div>
    </section>

@endsection
