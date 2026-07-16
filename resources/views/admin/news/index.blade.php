@extends('admin.layouts.app')

@section('title', 'News & Press')
@section('heading', 'News & Press')
@section('subheading', 'These stories appear on the public News & Press page.')

@section('actions')
    <a href="{{ route('admin.news.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
        New Story
    </a>
@endsection

@section('content')
    @if ($posts->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5h13v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" stroke-linejoin="round"/><path d="M17 9h2a1 1 0 0 1 1 1v8a2 2 0 0 1-2 2M8 9h6M8 13h6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-semibold text-navy-dark">No stories yet</h3>
            <p class="mt-1 text-sm text-gray-500">Publish your first news item or press release.</p>
            <a href="{{ route('admin.news.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                Add a Story
            </a>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Story</th>
                            <th class="px-5 py-3 font-semibold">Category</th>
                            <th class="px-5 py-3 font-semibold">Published</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($posts as $post)
                            <tr class="transition hover:bg-gray-50/70">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($post->image_url)
                                            <img src="{{ $post->image_url }}" alt="" class="h-11 w-16 shrink-0 rounded-lg object-cover ring-1 ring-gray-200">
                                        @else
                                            <span class="grid h-11 w-16 shrink-0 place-items-center rounded-lg bg-navy/5 text-brand ring-1 ring-gray-200">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5h13v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" stroke-linejoin="round"/></svg>
                                            </span>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-semibold text-navy-dark">
                                                {{ \Illuminate\Support\Str::limit($post->title, 46) }}
                                                @if ($post->is_featured)
                                                    <span class="ml-1 rounded bg-brand/10 px-1.5 py-0.5 text-[10px] font-bold text-brand">FEATURED</span>
                                                @endif
                                            </p>
                                            <p class="truncate text-xs text-gray-400">/{{ $post->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">{{ $post->category }}</span>
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-500">{{ $post->published_at?->format('j M Y') ?? '—' }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $post->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $post->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                        {{ $post->is_active ? 'Live' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.news.edit', $post) }}"
                                           class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20h4l10-10-4-4L4 16v4zM13.5 6.5l4 4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.news.destroy', $post) }}"
                                              onsubmit="return confirm('Delete this story? This cannot be undone.');">
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

        @if ($posts->hasPages())
            <div class="mt-5">{{ $posts->links() }}</div>
        @endif
    @endif
@endsection
