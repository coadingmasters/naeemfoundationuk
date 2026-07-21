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
                                    <div class="mt-3 flex items-center justify-between gap-2 pt-1">
                                        <span class="text-lg font-extrabold text-navy-dark">£{{ number_format($product->price, 2) }}</span>
                                        <form method="POST" action="{{ route('shop.cart.add') }}" data-bag-form>
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" @disabled(! $product->in_stock)
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-navy px-3.5 py-2 text-xs font-semibold text-white transition hover:bg-navy-dark disabled:cursor-not-allowed disabled:opacity-40">
                                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" stroke-linejoin="round"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0" stroke-linecap="round"/></svg>
                                                Add
                                            </button>
                                        </form>
                                    </div>
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
