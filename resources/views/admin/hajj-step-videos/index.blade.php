@extends('admin.layouts.app')

@section('title', 'Hajj Steps')
@section('heading', 'Hajj Steps')
@section('subheading', 'The "Steps of Hajj" video gallery on the Hajj 2027 page. Add as many videos as you like — they show automatically.')

@section('actions')
    <a href="{{ route('admin.hajj-step-videos.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
        Add Video
    </a>
@endsection

@section('content')
    @if ($videos->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15 10l4.55-2.28A1 1 0 0 1 21 8.62v6.76a1 1 0 0 1-1.45.9L15 14M4 6h9a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-semibold text-navy-dark">No videos yet</h3>
            <p class="mt-1 text-sm text-gray-500">Add a YouTube/Vimeo link or upload a video to populate the "Steps of Hajj" gallery.</p>
            <a href="{{ route('admin.hajj-step-videos.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                Add a Video
            </a>
        </div>
    @else
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($videos as $i => $video)
                <div class="nf-in-up overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg"
                     style="animation-delay: {{ min($i * 50, 500) }}ms">
                    <div class="relative flex h-28 items-center justify-center overflow-hidden bg-gradient-to-br from-navy to-navy-dark">
                        <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-brand/20 blur-2xl"></div>
                        <div class="pointer-events-none absolute -bottom-8 -left-6 h-24 w-24 rounded-full bg-white/5 blur-2xl"></div>
                        <span class="relative grid h-11 w-11 place-items-center rounded-full bg-white/10 text-white ring-1 ring-white/20">
                            <svg class="h-5 w-5 translate-x-0.5" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 0 0 1.54.84l10.72-6.86a1 1 0 0 0 0-1.68L9.54 4.3A1 1 0 0 0 8 5.14z"/></svg>
                        </span>
                        <span class="absolute right-2 top-2 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $video->is_active ? 'bg-green-100 text-green-700' : 'bg-white/90 text-gray-500' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $video->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                            {{ $video->is_active ? 'Live' : 'Hidden' }}
                        </span>
                        <span class="absolute left-2 top-2 inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/90 text-xs font-semibold text-navy-dark">
                            {{ $video->sort_order }}
                        </span>
                    </div>

                    <div class="p-5">
                        <p class="truncate font-semibold text-navy-dark">{{ $video->title ?: 'Untitled video' }}</p>
                        <p class="mt-1 truncate text-xs text-gray-400" title="{{ $video->video_url }}">
                            {{ \Illuminate\Support\Str::limit($video->video_url, 46) }}
                        </p>

                        <div class="mt-4 flex items-center gap-2">
                            <a href="{{ route('admin.hajj-step-videos.edit', $video) }}"
                               class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20h4l10-10-4-4L4 16v4zM13.5 6.5l4 4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.hajj-step-videos.destroy', $video) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-admin-delete data-label="{{ $video->title ?: 'this video' }}"
                                        class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($videos->hasPages())
            <div class="mt-8">
                {{ $videos->links() }}
            </div>
        @endif
    @endif
@endsection
