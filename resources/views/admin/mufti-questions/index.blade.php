@extends('admin.layouts.app')

@section('title', 'Ask a Mufti')
@section('heading', 'Ask a Mufti')
@section('subheading', 'Questions submitted through the Ask a Mufti page.')

@section('actions')
    @if ($questions->total() > 0)
        <a href="{{ route('admin.mufti-questions.export') }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-navy transition hover:border-brand hover:text-brand">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Export CSV
        </a>
    @endif
@endsection

@section('content')

    @php
        $cards = [
            ['label' => 'Total questions', 'value' => $stats['total'], 'icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['label' => 'Last 7 days', 'value' => $stats['week'], 'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['label' => 'Today', 'value' => $stats['today'], 'icon' => '<rect x="4" y="5" width="16" height="16" rx="2"/><path d="M8 3v4M16 3v4M4 11h16" stroke-linecap="round"/>'],
        ];
    @endphp
    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        @foreach ($cards as $card)
            <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand/10 text-brand">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $card['icon'] !!}</svg>
                </span>
                <div>
                    <p class="text-2xl font-extrabold text-navy-dark">{{ number_format($card['value']) }}</p>
                    <p class="text-xs font-medium text-gray-500">{{ $card['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    @if ($questions->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-semibold text-navy-dark">No questions yet</h3>
            <p class="mt-1 text-sm text-gray-500">When visitors submit a question on the Ask a Mufti page, it&rsquo;ll appear here.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">From</th>
                            <th class="px-5 py-3 font-semibold">Question</th>
                            <th class="px-5 py-3 font-semibold">Submitted</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($questions as $q)
                            @php
                                $initials = \Illuminate\Support\Str::of($q->name)->explode(' ')->filter()->take(2)
                                    ->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('');
                            @endphp
                            <tr class="nf-adm-row transition hover:bg-gray-50/70">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-cream text-xs font-bold uppercase text-brand">{{ $initials ?: '?' }}</span>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-navy-dark">{{ $q->name }}</p>
                                            <a href="mailto:{{ $q->email }}" class="truncate text-xs text-gray-400 hover:text-brand">{{ $q->email }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-gray-600">
                                    <p class="max-w-md truncate">{{ $q->message }}</p>
                                </td>
                                <td class="px-5 py-3 text-gray-500">
                                    {{ $q->created_at?->format('d M Y') }}
                                    @if ($q->answered_at)
                                        <span class="ml-1 inline-block rounded-full bg-green-100 px-2 py-0.5 text-[11px] font-semibold text-green-700">Replied</span>
                                    @else
                                        <span class="ml-1 inline-block rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-700">New</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.mufti-questions.show', $q) }}"
                                           class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                            Read &amp; reply
                                        </a>
                                        <form method="POST" action="{{ route('admin.mufti-questions.destroy', $q) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-admin-delete data-label="{{ \Illuminate\Support\Str::limit($q->name.'’s question', 40) }}"
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

        @if ($questions->hasPages())
            <div class="mt-5">{{ $questions->links() }}</div>
        @endif
    @endif

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.nf-adm-row').forEach((row, i) => setTimeout(() => row.classList.add('in'), 40 + i * 45));
</script>
@endpush
