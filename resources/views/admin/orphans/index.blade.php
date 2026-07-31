@extends('admin.layouts.app')

@section('title', 'Orphans')
@section('heading', 'Orphans')
@section('subheading', 'Children shown on the "Sponsor an Orphan" page. Totals show what each child has received.')

@section('actions')
    <a href="{{ route('admin.orphans.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
        New Orphan
    </a>
@endsection

@section('content')
    @if ($orphans->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3.2"/><path d="M5.5 20a6.5 6.5 0 0 1 13 0" stroke-linecap="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-semibold text-navy-dark">No orphans yet</h3>
            <p class="mt-1 text-sm text-gray-500">Add your first child to populate the sponsorship page.</p>
            <a href="{{ route('admin.orphans.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-dark">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                Add an Orphan
            </a>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Orphan</th>
                            <th class="px-5 py-3 font-semibold">Order</th>
                            <th class="px-5 py-3 font-semibold">Received</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($orphans as $orphan)
                            @php $s = $stats[$orphan->id] ?? null; @endphp
                            <tr class="transition hover:bg-gray-50/70">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($orphan->photo)
                                            <img src="{{ asset($orphan->photo) }}" alt="" class="h-14 w-12 shrink-0 rounded-lg object-cover ring-1 ring-gray-200">
                                        @else
                                            <span class="grid h-14 w-12 shrink-0 place-items-center rounded-lg bg-cream text-navy/20 ring-1 ring-gray-200">
                                                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4 0-8 2-8 5v1h16v-1c0-3-4-5-8-5z"/></svg>
                                            </span>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-semibold text-navy-dark">{{ \Illuminate\Support\Str::limit($orphan->name, 40) }}</p>
                                            <p class="truncate text-xs text-gray-400">
                                                {{ $orphan->location ?: '—' }}@if ($orphan->grade) &middot; {{ $orphan->grade }}@endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-md bg-gray-100 text-xs font-semibold text-navy-dark">{{ $orphan->sort_order }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    @if ($s && ($s['count'] ?? 0) > 0)
                                        <p class="font-semibold text-navy-dark">{{ money($s['raised']) }}</p>
                                        <p class="text-xs text-gray-400">{{ $s['count'] }} {{ \Illuminate\Support\Str::plural('donation', $s['count']) }}</p>
                                    @else
                                        <span class="text-xs text-gray-400">No donations yet</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $orphan->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $orphan->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                        {{ $orphan->is_active ? 'Live' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        @if ($orphan->slug)
                                            <a href="{{ route('orphans.show', $orphan) }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                                View
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.orphans.edit', $orphan) }}"
                                           class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 20h4l10-10-4-4L4 16v4zM13.5 6.5l4 4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.orphans.destroy', $orphan) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-admin-delete data-label="{{ \Illuminate\Support\Str::limit($orphan->name, 40) }}"
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

        @if ($orphans->hasPages())
            <div class="mt-5">
                {{ $orphans->links() }}
            </div>
        @endif
    @endif
@endsection
