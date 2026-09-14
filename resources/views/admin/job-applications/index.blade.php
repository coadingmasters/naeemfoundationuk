@extends('admin.layouts.app')

@section('title', 'Job Applications')
@section('heading', 'Job Applications')
@section('subheading', 'Everyone who applied through the Careers page form.')

@section('content')

    @php
        $cards = [
            ['label' => 'Total applications', 'value' => $stats['total'],
             'icon' => '<path d="M4 5h13v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" stroke-linejoin="round"/><path d="M8 9h6M8 13h6M8 17h3" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['label' => 'Last 7 days', 'value' => $stats['week'],
             'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['label' => 'With a CV attached', 'value' => $stats['with_cv'],
             'icon' => '<path d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke-linecap="round" stroke-linejoin="round"/>'],
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

    @if ($applications->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 5h13v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" stroke-linejoin="round"/><path d="M8 9h6M8 13h6M8 17h3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-semibold text-navy-dark">No applications yet</h3>
            <p class="mt-1 text-sm text-gray-500">When visitors apply on the Careers page, they&rsquo;ll appear here.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Name</th>
                            <th class="px-5 py-3 font-semibold">Contact</th>
                            <th class="px-5 py-3 font-semibold">Postcode</th>
                            <th class="px-5 py-3 font-semibold">CV</th>
                            <th class="px-5 py-3 font-semibold">Submitted</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($applications as $a)
                            @php
                                $initials = \Illuminate\Support\Str::of($a->name)->explode(' ')->filter()->take(2)
                                    ->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('');
                            @endphp
                            <tr class="nf-adm-row transition hover:bg-gray-50/70">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-cream text-xs font-bold uppercase text-brand">{{ $initials ?: '?' }}</span>
                                        <span class="font-semibold text-navy-dark">{{ $a->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <a href="mailto:{{ $a->email }}" class="block text-navy hover:text-brand">{{ $a->email }}</a>
                                    <a href="tel:{{ $a->phone }}" class="block text-xs text-gray-400 hover:text-brand">{{ $a->phone }}</a>
                                </td>
                                <td class="px-5 py-3 text-gray-500">{{ $a->postcode ?: '—' }}</td>
                                <td class="px-5 py-3">
                                    @if ($a->cv_path)
                                        <a href="{{ asset($a->cv_path) }}" target="_blank" rel="noopener"
                                           class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            View CV
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-300">&mdash;</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-gray-500">{{ $a->created_at?->format('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" data-delete
                                                data-action="{{ route('admin.job-applications.destroy', $a) }}"
                                                data-name="{{ $a->name }}"
                                                class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if ($applications->hasPages())
            <div class="mt-5">{{ $applications->links() }}</div>
        @endif
    @endif

    {{-- ===== Delete confirm modal ===== --}}
    <div class="nf-modal" data-delete-modal hidden>
        <div class="nf-modal__backdrop" data-modal-close></div>
        <div class="nf-modal__card text-center" role="dialog" aria-modal="true" aria-label="Delete application">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-red-50 text-red-600">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-bold text-navy-dark">Delete application?</h3>
            <p class="mt-1.5 text-sm text-gray-500">
                This will permanently remove <span class="font-semibold text-navy-dark" data-del-name></span>&rsquo;s application, including their CV file. This cannot be undone.
            </p>

            <form method="POST" data-delete-form class="mt-6 flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" data-modal-close class="flex-1 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-navy transition hover:bg-gray-50">Cancel</button>
                <button type="submit" class="flex-1 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
(function () {
    const delModal = document.querySelector('[data-delete-modal]');
    const delForm = document.querySelector('[data-delete-form]');

    const open = (m) => { m.hidden = false; requestAnimationFrame(() => m.classList.add('is-open')); };
    const close = (m) => { m.classList.remove('is-open'); setTimeout(() => { m.hidden = true; }, 320); };

    document.querySelectorAll('[data-delete]').forEach((btn) => btn.addEventListener('click', () => {
        delForm.action = btn.dataset.action;
        delModal.querySelector('[data-del-name]').textContent = btn.dataset.name;
        open(delModal);
    }));

    document.querySelectorAll('[data-modal-close]').forEach((el) => el.addEventListener('click', () => {
        if (!delModal.hidden) close(delModal);
    }));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !delModal.hidden) close(delModal);
    });

    document.querySelectorAll('.nf-adm-row').forEach((row, i) => {
        setTimeout(() => row.classList.add('in'), 40 + i * 45);
    });
})();
</script>
@endpush
