@extends('admin.layouts.app')

@section('title', 'Edit Hero Banner — ' . $pageLabel)
@section('heading', $pageLabel . ' — Hero Banner')
@section('subheading', 'Set the background photo shown behind this page\'s heading. Leave it hidden or reset it to fall back to the default.')

@section('content')
    <form method="POST" action="{{ route('admin.page-heroes.update', $pageKey) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.page-heroes._form', ['mode' => 'edit'])
    </form>

    @if ($hero->exists)
        <form method="POST" action="{{ route('admin.page-heroes.destroy', $pageKey) }}" class="mt-5">
            @csrf
            @method('DELETE')
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div>
                    <p class="text-sm font-semibold text-navy-dark">Reset to default</p>
                    <p class="mt-0.5 text-xs text-gray-500">Remove this custom banner and show the built-in default photo again.</p>
                </div>
                <button type="button" data-admin-delete data-label="the custom hero banner for {{ $pageLabel }}"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 3-6.7M3 4v4h4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Reset to default
                </button>
            </div>
        </form>
    @endif
@endsection
