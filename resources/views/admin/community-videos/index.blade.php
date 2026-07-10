@extends('admin.layouts.app')

@section('title', 'Community Videos')
@section('heading', 'Community Videos')
@section('subheading', 'These videos appear in the gallery on the Community Centre page.')

@section('actions')
    <a href="{{ route('admin.community-videos.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
        New Video
    </a>
@endsection

@section('content')
    @if ($videos->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15 10l4.55-2.28A1 1 0 0 1 21 8.62v6.76a1 1 0 0 1-1.45.9L15 14M4 6h9a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-semibold text-navy-dark">No videos yet</h3>
            <p class="mt-1 text-sm text-gray-500">Add a YouTube/Vimeo link or upload a video to populate the Community Centre gallery.</p>
            <a href="{{ route('admin.community-videos.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                Add a Video
            </a>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Video</th>
                            <th class="px-5 py-3 font-semibold">Source</th>
                            <th class="px-5 py-3 font-semibold">Order</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($videos as $video)
                            <tr class="transition hover:bg-gray-50/70">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-11 w-16 shrink-0 place-items-center rounded-lg bg-navy/5 text-brand ring-1 ring-gray-200">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 8l6 4-6 4V8z" stroke-linejoin="round"/></svg>
                                        </span>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-navy-dark">{{ \Illuminate\Support\Str::limit($video->title, 44) }}</p>
                                            <p class="truncate text-xs text-gray-400">{{ \Illuminate\Support\Str::limit($video->video_url, 54) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                        {{ $video->is_embed ? 'Embed link' : (\Illuminate\Support\Str::startsWith($video->video_url, ['http://', 'https://']) ? 'File link' : 'Uploaded') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-gray-100 text-xs font-semibold text-navy-dark">{{ $video->sort_order }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $video->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $video->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                        {{ $video->is_active ? 'Live' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.community-videos.edit', $video) }}"
                                           class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20h4l10-10-4-4L4 16v4zM13.5 6.5l4 4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.community-videos.destroy', $video) }}"
                                              onsubmit="return confirm('Delete this video? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($videos->hasPages())
            <div class="mt-5">
                {{ $videos->links() }}
            </div>
        @endif
    @endif
@endsection
