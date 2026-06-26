@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('subheading', 'Welcome back, ' . auth()->user()->name . '.')

@section('content')
    @php
        $cards = [
            ['label' => 'Total Slides', 'value' => $stats['total_slides'], 'tone' => 'brand', 'icon' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 15l5-4 4 3 3-2 6 5" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['label' => 'Live Slides', 'value' => $stats['active_slides'], 'tone' => 'green', 'icon' => '<path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['label' => 'Total Appeals', 'value' => $stats['total_appeals'], 'tone' => 'navy', 'icon' => '<path d="M12 21s-7-4.35-9-8.5C1.5 9 3.5 6 6.5 6 9 6 12 9 12 9s3-3 5.5-3C20.5 6 22.5 9 21 12.5 19 16.65 12 21 12 21z" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['label' => 'Live Appeals', 'value' => $stats['active_appeals'], 'tone' => 'green', 'icon' => '<path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>'],
        ];
        $tones = [
            'brand' => 'bg-brand text-white',
            'green' => 'bg-green-500 text-white',
            'navy' => 'bg-navy text-white',
        ];
    @endphp

    {{-- Stat cards --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($cards as $card)
            <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl {{ $tones[$card['tone']] }}">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $card['icon'] !!}</svg>
                </span>
                <div>
                    <p class="text-3xl font-bold leading-none text-navy-dark">{{ $card['value'] }}</p>
                    <p class="mt-1 text-sm text-gray-500">{{ $card['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Welcome / quick actions --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl bg-gradient-to-br from-navy via-navy-dark to-brand p-6 text-white lg:col-span-2">
            <h2 class="text-lg font-bold">Manage your homepage hero slider</h2>
            <p class="mt-2 max-w-lg text-sm text-white/80">
                Add, edit, reorder or remove the slides shown at the top of your website. Changes go live instantly — no code required.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('admin.hero-slides.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-brand transition hover:bg-cream">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                    Add a Slide
                </a>
                <a href="{{ route('admin.hero-slides.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/20">
                    Manage Slides
                </a>
                <a href="{{ route('admin.appeals.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/20">
                    Manage Appeals
                </a>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="text-sm font-semibold text-navy-dark">Recent slides</h3>
            <div class="mt-4 space-y-3">
                @forelse ($recentSlides as $slide)
                    <div class="flex items-center gap-3">
                        <img src="{{ asset($slide->image) }}" alt="" class="h-10 w-14 shrink-0 rounded-md object-cover">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-navy-dark">{{ \Illuminate\Support\Str::limit(str_replace("\n", ' ', $slide->title), 28) }}</p>
                        </div>
                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $slide->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $slide->is_active ? 'Live' : 'Hidden' }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">No slides yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
