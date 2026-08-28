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
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Page</th>
                        <th class="px-5 py-3 font-semibold">Current video</th>
                        <th class="px-5 py-3 font-semibold">Source</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($rows as $row)
                        @php $custom = $row['video']; @endphp
                        <tr class="transition hover:bg-gray-50/70">
                            <td class="px-5 py-3">
                                <p class="font-semibold text-navy-dark">{{ $row['label'] }}</p>
                                <p class="text-xs text-gray-400">/{{ $row['key'] }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <p class="max-w-xs truncate text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($row['resolved']['url'], 60) }}</p>
                            </td>
                            <td class="px-5 py-3">
                                @if ($custom)
                                    <span class="inline-flex items-center rounded-full bg-brand/10 px-2.5 py-1 text-xs font-semibold text-brand">Custom</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500">Default</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @if (! $custom)
                                    <span class="text-xs text-gray-400">—</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $custom->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $custom->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                        {{ $custom->is_active ? 'Live' : 'Hidden' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.page-videos.edit', $row['key']) }}"
                                       class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
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
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
