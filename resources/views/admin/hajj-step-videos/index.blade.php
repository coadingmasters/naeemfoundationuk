@extends('admin.layouts.app')

@section('title', 'Hajj Steps')
@section('heading', 'Hajj Steps')
@section('subheading', 'A video for any of these 8 steps replaces its plain description on the Hajj page, gallery-style.')

@section('content')
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($rows as $i => $row)
            @php $custom = $row['video']; @endphp
            <div class="flex flex-col rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-2">
                    <span class="text-xl font-extrabold text-brand/25">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $custom ? 'bg-brand/10 text-brand' : 'bg-gray-100 text-gray-500' }}">
                        {{ $custom ? 'Custom' : 'Text only' }}
                    </span>
                </div>
                <p class="mt-1.5 font-semibold text-navy-dark">{{ $row['label'] }}</p>

                @if ($custom)
                    <span class="mt-2 inline-flex w-fit items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $custom->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $custom->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                        {{ $custom->is_active ? 'Live' : 'Hidden' }}
                    </span>
                @endif

                <a href="{{ route('admin.hajj-step-videos.edit', $row['key']) }}"
                   class="mt-4 inline-flex items-center justify-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20h4l10-10-4-4L4 16v4zM13.5 6.5l4 4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ $custom ? 'Edit' : 'Set video' }}
                </a>
            </div>
        @endforeach
    </div>
@endsection
