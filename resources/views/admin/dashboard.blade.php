@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('subheading', 'Overview of your website content.')

@section('content')
    @php
        $toneBg = [
            'brand' => 'bg-brand',
            'navy' => 'bg-navy',
            'emerald' => 'bg-emerald-500',
            'amber' => 'bg-amber-500',
            'sky' => 'bg-sky-500',
        ];
        $toneText = [
            'brand' => 'text-brand',
            'navy' => 'text-navy',
            'emerald' => 'text-emerald-600',
            'amber' => 'text-amber-600',
            'sky' => 'text-sky-600',
        ];
    @endphp

    {{-- ===== Greeting banner ===== --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-navy via-navy-dark to-brand p-6 text-white sm:p-8">
        <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-20 right-24 h-56 w-56 rounded-full bg-brand/30 blur-3xl"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm text-white/70">{{ now()->format('l, j F Y') }}</p>
                <h2 class="mt-1 text-2xl font-bold sm:text-3xl">Welcome back, {{ auth()->user()->name }} 👋</h2>
                <p class="mt-2 max-w-md text-sm text-white/75">Here's what's currently published across your website.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="rounded-xl bg-white/10 px-5 py-3 text-center ring-1 ring-white/15">
                    <p class="text-2xl font-bold leading-none">{{ $totals['items'] }}</p>
                    <p class="mt-1 text-xs text-white/70">Total items</p>
                </div>
                <div class="rounded-xl bg-white/10 px-5 py-3 text-center ring-1 ring-white/15">
                    <p class="text-2xl font-bold leading-none text-green-300">{{ $totals['live'] }}</p>
                    <p class="mt-1 text-xs text-white/70">Live</p>
                </div>
                <div class="rounded-xl bg-white/10 px-5 py-3 text-center ring-1 ring-white/15">
                    <p class="text-2xl font-bold leading-none text-white/80">{{ $totals['hidden'] }}</p>
                    <p class="mt-1 text-xs text-white/70">Hidden</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Section stat cards ===== --}}
    <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($sections as $s)
            <div class="flex flex-col rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-start justify-between">
                    <span class="grid h-12 w-12 place-items-center rounded-xl {{ $toneBg[$s['tone']] }} text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $s['icon'] !!}</svg>
                    </span>
                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-semibold text-green-700">{{ $s['active'] }} live</span>
                </div>

                <p class="mt-4 text-3xl font-bold leading-none text-navy-dark">{{ $s['total'] }}</p>
                <p class="mt-1 text-sm text-gray-500">{{ $s['label'] }}</p>

                <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3">
                    <a href="{{ $s['index'] }}" class="text-sm font-semibold {{ $toneText[$s['tone']] }} hover:underline">Manage</a>
                    <a href="{{ $s['create'] }}" class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 transition hover:text-navy-dark">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                        Add
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== Recent activity + quick actions ===== --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Recent --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-navy-dark">Recently added</h3>
                <span class="text-xs text-gray-400">Latest across all sections</span>
            </div>

            @if ($recent->isEmpty())
                <p class="py-8 text-center text-sm text-gray-400">No content yet — add your first item to get started.</p>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($recent as $item)
                        <a href="{{ $item['edit_url'] }}" class="group flex items-center gap-3 py-3">
                            <img src="{{ asset($item['image']) }}" alt="" class="h-11 w-16 shrink-0 rounded-lg object-cover ring-1 ring-gray-200">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-navy-dark group-hover:text-brand">{{ $item['title'] ?: '—' }}</p>
                                <p class="text-xs text-gray-400">
                                    <span class="font-medium text-gray-500">{{ $item['type'] }}</span>
                                    @if ($item['time']) · {{ $item['time']->diffForHumans() }} @endif
                                </p>
                            </div>
                            <span class="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $item['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $item['is_active'] ? 'Live' : 'Hidden' }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Quick actions --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-sm font-semibold text-navy-dark">Quick actions</h3>
                <div class="space-y-2.5">
                    @foreach ($sections as $s)
                        <a href="{{ $s['create'] }}" class="flex items-center gap-3 rounded-xl border border-gray-100 px-3.5 py-2.5 transition hover:border-brand/30 hover:bg-cream/50">
                            <span class="grid h-8 w-8 place-items-center rounded-lg {{ $toneBg[$s['tone']] }} text-white">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                            </span>
                            <span class="text-sm font-medium text-navy-dark">New {{ \Illuminate\Support\Str::singular($s['label']) }}</span>
                            <svg class="ml-auto h-4 w-4 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl bg-gradient-to-br from-brand to-brand-dark p-6 text-white shadow-sm">
                <h3 class="text-base font-bold">Your website is live</h3>
                <p class="mt-1.5 text-sm text-white/80">Preview how your changes look to visitors.</p>
                <a href="{{ route('home') }}" target="_blank" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-brand transition hover:bg-cream">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 5h5v5m0-5l-8 8M19 13v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    View website
                </a>
            </div>
        </div>
    </div>
@endsection
