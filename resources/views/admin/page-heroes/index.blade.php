@extends('admin.layouts.app')

@section('title', 'Hero Banners')
@section('heading', 'Hero Banners')
@section('subheading', 'The background photo behind the heading on each Giving page. Upload a custom one to override the built-in default.')

@section('actions')
    <a href="{{ route('admin.page-heroes.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
        Set a Hero Banner
    </a>
@endsection

@section('content')
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($rows as $row)
            @php $custom = $row['hero']; @endphp
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="relative h-32 bg-gray-100">
                    @if ($custom)
                        <img src="{{ asset($custom->image) }}" alt="{{ $row['label'] }}" class="h-full w-full object-cover">
                    @else
                        <div class="grid h-full w-full place-items-center text-gray-300">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 15l5-4 4 3 3-2 6 5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    @endif
                    <span class="absolute right-2 top-2 inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $custom ? 'bg-brand/90 text-white' : 'bg-white/90 text-gray-500' }}">
                        {{ $custom ? 'Custom' : 'Default' }}
                    </span>
                    @if ($custom && ! $custom->is_active)
                        <span class="absolute left-2 top-2 inline-flex items-center gap-1.5 rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-gray-500">
                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Hidden
                        </span>
                    @endif
                </div>
                <div class="flex items-center justify-between gap-3 p-4">
                    <div class="min-w-0">
                        <p class="truncate font-semibold text-navy-dark">{{ $row['label'] }}</p>
                        <p class="truncate text-xs text-gray-400">/{{ $row['key'] }}</p>
                    </div>
                    <a href="{{ route('admin.page-heroes.edit', $row['key']) }}"
                       class="inline-flex shrink-0 items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20h4l10-10-4-4L4 16v4zM13.5 6.5l4 4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ $custom ? 'Edit' : 'Set banner' }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
