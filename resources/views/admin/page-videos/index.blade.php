@extends('admin.layouts.app')

@section('title', 'Page Videos')
@section('heading', 'Page Videos')
@section('subheading', 'The video shown under the hero on each Giving page. Set a custom one to override the built-in default.')

@section('actions')
    <a href="{{ route('admin.page-videos.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
        Set a Page Video
    </a>
@endsection

@section('content')
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($rows as $i => $row)
            @php $custom = $row['video']; @endphp
            <div class="nf-in-up overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg"
                 style="animation-delay: {{ min($i * 50, 500) }}ms">
                {{-- Video preview header — a branded play-button treatment rather
                     than a fetched thumbnail, since the source can be a YouTube,
                     Vimeo, Facebook or uploaded-file link. --}}
                <div class="relative flex h-28 items-center justify-center overflow-hidden bg-gradient-to-br from-navy to-navy-dark">
                    <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-brand/20 blur-2xl"></div>
                    <div class="pointer-events-none absolute -bottom-8 -left-6 h-24 w-24 rounded-full bg-white/5 blur-2xl"></div>
                    <span class="relative grid h-11 w-11 place-items-center rounded-full bg-white/10 text-white ring-1 ring-white/20 transition group-hover:scale-105">
                        <svg class="h-5 w-5 translate-x-0.5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 0 0 1.54.84l10.72-6.86a1 1 0 0 0 0-1.68L9.54 4.3A1 1 0 0 0 8 5.14z"/></svg>
                    </span>
                    <span class="absolute right-2 top-2 inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $custom ? 'bg-brand/90 text-white' : 'bg-white/90 text-gray-500' }}">
                        {{ $custom ? 'Custom' : 'Default' }}
                    </span>
                    @if ($custom && ! $custom->is_active)
                        <span class="absolute left-2 top-2 inline-flex items-center gap-1.5 rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-gray-500">
                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Hidden
                        </span>
                    @endif
                </div>

                <div class="p-5">
                    <p class="font-semibold text-navy-dark">{{ $row['label'] }}</p>
                    <p class="text-xs text-gray-400">/{{ $row['key'] }}</p>
                    <p class="mt-2 truncate text-xs text-gray-500" title="{{ $row['resolved']['url'] }}">
                        {{ \Illuminate\Support\Str::limit($row['resolved']['url'], 46) }}
                    </p>

                    <div class="mt-4 flex items-center gap-2">
                        <a href="{{ route('admin.page-videos.edit', $row['key']) }}"
                           class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20h4l10-10-4-4L4 16v4zM13.5 6.5l4 4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            {{ $custom ? 'Edit' : 'Set video' }}
                        </a>
                        @if ($custom)
                            <form method="POST" action="{{ route('admin.page-videos.destroy', $row['key']) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-admin-delete data-label="the custom video for {{ $row['label'] }}"
                                        class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 3-6.7M3 4v4h4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    Reset
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
