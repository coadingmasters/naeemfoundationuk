@extends('admin.layouts.app')

@section('title', 'Hajj Registrations')
@section('heading', 'Hajj 2027 Registrations')
@section('subheading', 'Everyone who registered their interest through the Hajj 2027 form.')

@section('actions')
    @if ($registrations->total() > 0)
        <a href="{{ route('admin.hajj-registrations.export') }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-navy transition hover:border-brand hover:text-brand">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Export CSV
        </a>
    @endif
@endsection

@section('content')

    {{-- ===== Stats ===== --}}
    @php
        $cards = [
            ['label' => 'Total registrations', 'value' => $stats['total'],
             'icon' => '<circle cx="9" cy="8" r="3"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0" stroke-linecap="round"/><path d="M16 3.2a3 3 0 0 1 0 5.6M17.5 20a5.5 5.5 0 0 0-2.7-4.8" stroke-linecap="round"/>'],
            ['label' => 'Pakistani passport', 'value' => $stats['passport'],
             'icon' => '<rect x="5" y="3" width="14" height="18" rx="2"/><circle cx="12" cy="10" r="2.5"/><path d="M9 15h6" stroke-linecap="round"/>'],
            ['label' => 'Last 7 days', 'value' => $stats['week'],
             'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/>'],
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

    @if ($registrations->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1M9 10h6M9 14h6M9 18h3" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-semibold text-navy-dark">No registrations yet</h3>
            <p class="mt-1 text-sm text-gray-500">When visitors register on the Hajj 2027 page, they&rsquo;ll appear here.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Name</th>
                            <th class="px-5 py-3 font-semibold">Contact</th>
                            <th class="px-5 py-3 font-semibold">Passport</th>
                            <th class="px-5 py-3 font-semibold">Submitted</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($registrations as $r)
                            @php $fullName = trim($r->first_name.' '.$r->last_name); @endphp
                            <tr class="nf-adm-row transition hover:bg-gray-50/70">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-cream text-xs font-bold uppercase text-brand">
                                            {{ \Illuminate\Support\Str::substr($r->first_name, 0, 1) }}{{ \Illuminate\Support\Str::substr($r->last_name, 0, 1) }}
                                        </span>
                                        <span class="font-semibold text-navy-dark">{{ $fullName }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <a href="mailto:{{ $r->email }}" class="block text-navy hover:text-brand">{{ $r->email }}</a>
                                    <a href="tel:{{ $r->phone }}" class="block text-xs text-gray-400 hover:text-brand">{{ $r->phone }}</a>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $r->has_pakistani_passport ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $r->has_pakistani_passport ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                        {{ $r->has_pakistani_passport ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-gray-500">{{ $r->created_at?->format('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" data-view
                                                data-name="{{ $fullName }}"
                                                data-email="{{ $r->email }}"
                                                data-phone="{{ $r->phone }}"
                                                data-passport="{{ $r->has_pakistani_passport ? 'Yes' : 'No' }}"
                                                data-date="{{ $r->created_at?->format('d M Y · g:i a') }}"
                                                class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                            View
                                        </button>
                                        <button type="button" data-delete
                                                data-action="{{ route('admin.hajj-registrations.destroy', $r) }}"
                                                data-name="{{ $fullName }}"
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

        @if ($registrations->hasPages())
            <div class="mt-5">{{ $registrations->links() }}</div>
        @endif
    @endif

    {{-- ===== View details modal ===== --}}
    <div class="nf-modal" data-view-modal hidden>
        <div class="nf-modal__backdrop" data-modal-close></div>
        <div class="nf-modal__card" role="dialog" aria-modal="true" aria-label="Registration details">
            <button type="button" class="nf-modal__close" data-modal-close aria-label="Close">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
            </button>

            <div class="flex items-center gap-3">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-brand/10 text-brand">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="5" y="4" width="14" height="17" rx="2"/><path d="M9 4V3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1M9 10h6M9 14h6M9 18h3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <div>
                    <h3 class="text-lg font-bold text-navy-dark">Registration details</h3>
                    <p class="text-xs text-gray-400">Hajj 2027 sign-up</p>
                </div>
            </div>

            <dl class="mt-5 space-y-3.5">
                <div class="flex justify-between gap-4 border-b border-gray-100 pb-3.5">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Name</dt>
                    <dd class="text-right font-semibold text-navy-dark" data-f-name></dd>
                </div>
                <div class="flex justify-between gap-4 border-b border-gray-100 pb-3.5">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Email</dt>
                    <dd class="text-right"><a class="font-semibold text-brand hover:underline" data-f-email></a></dd>
                </div>
                <div class="flex justify-between gap-4 border-b border-gray-100 pb-3.5">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Phone</dt>
                    <dd class="text-right"><a class="font-semibold text-brand hover:underline" data-f-phone></a></dd>
                </div>
                <div class="flex justify-between gap-4 border-b border-gray-100 pb-3.5">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Pakistani passport</dt>
                    <dd class="text-right font-semibold text-navy-dark" data-f-passport></dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Submitted</dt>
                    <dd class="text-right font-semibold text-navy-dark" data-f-date></dd>
                </div>
            </dl>

            <button type="button" data-modal-close class="btn-navy mt-6 w-full py-2.5">Close</button>
        </div>
    </div>

    {{-- ===== Delete confirm modal ===== --}}
    <div class="nf-modal" data-delete-modal hidden>
        <div class="nf-modal__backdrop" data-modal-close></div>
        <div class="nf-modal__card text-center" role="dialog" aria-modal="true" aria-label="Delete registration">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-red-50 text-red-600">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-bold text-navy-dark">Delete registration?</h3>
            <p class="mt-1.5 text-sm text-gray-500">
                This will permanently remove <span class="font-semibold text-navy-dark" data-del-name></span>&rsquo;s registration. This cannot be undone.
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
    const viewModal = document.querySelector('[data-view-modal]');
    const delModal = document.querySelector('[data-delete-modal]');
    const delForm = document.querySelector('[data-delete-form]');
    const modals = [viewModal, delModal].filter(Boolean);

    const open = (m) => { m.hidden = false; requestAnimationFrame(() => m.classList.add('is-open')); };
    const close = (m) => { m.classList.remove('is-open'); setTimeout(() => { m.hidden = true; }, 320); };

    document.querySelectorAll('[data-view]').forEach((btn) => btn.addEventListener('click', () => {
        const d = btn.dataset;
        viewModal.querySelector('[data-f-name]').textContent = d.name;
        const email = viewModal.querySelector('[data-f-email]');
        email.textContent = d.email; email.href = 'mailto:' + d.email;
        const phone = viewModal.querySelector('[data-f-phone]');
        phone.textContent = d.phone; phone.href = 'tel:' + d.phone;
        viewModal.querySelector('[data-f-passport]').textContent = d.passport;
        viewModal.querySelector('[data-f-date]').textContent = d.date;
        open(viewModal);
    }));

    document.querySelectorAll('[data-delete]').forEach((btn) => btn.addEventListener('click', () => {
        delForm.action = btn.dataset.action;
        delModal.querySelector('[data-del-name]').textContent = btn.dataset.name;
        open(delModal);
    }));

    document.querySelectorAll('[data-modal-close]').forEach((el) => el.addEventListener('click', () => {
        modals.forEach((m) => { if (!m.hidden) close(m); });
    }));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') modals.forEach((m) => { if (!m.hidden) close(m); });
    });

    // Staggered row entrance.
    document.querySelectorAll('.nf-adm-row').forEach((row, i) => {
        setTimeout(() => row.classList.add('in'), 40 + i * 45);
    });
})();
</script>
@endpush
