@extends('admin.layouts.app')

@section('title', 'Orders')
@section('heading', 'Shop Orders')
@section('subheading', 'Orders placed through the shop checkout.')

@section('actions')
    @if ($orders->total() > 0)
        <a href="{{ route('admin.orders.export') }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-navy transition hover:border-brand hover:text-brand">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Export CSV
        </a>
    @endif
@endsection

@php
    $statusStyles = [
        'pending' => 'bg-amber-100 text-amber-700',
        'processing' => 'bg-blue-100 text-blue-700',
        'completed' => 'bg-green-100 text-green-700',
        'cancelled' => 'bg-gray-100 text-gray-500',
    ];
@endphp

@section('content')

    @php
        $cards = [
            ['label' => 'Total orders', 'value' => number_format($stats['total']),
             'icon' => '<path d="M4 4h12l4 4v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z" stroke-linejoin="round"/><path d="M8 9h8M8 13h8" stroke-linecap="round"/>'],
            ['label' => 'Pending', 'value' => number_format($stats['pending']),
             'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2" stroke-linecap="round" stroke-linejoin="round"/>'],
            ['label' => 'Revenue', 'value' => '£'.number_format($stats['revenue'], 2),
             'icon' => '<path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" stroke-linecap="round" stroke-linejoin="round"/>'],
        ];
    @endphp
    <div class="mb-6 grid gap-4 sm:grid-cols-3">
        @foreach ($cards as $card)
            <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand/10 text-brand">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $card['icon'] !!}</svg>
                </span>
                <div>
                    <p class="text-2xl font-extrabold text-navy-dark">{{ $card['value'] }}</p>
                    <p class="text-xs font-medium text-gray-500">{{ $card['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    @if ($orders->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-cream text-brand">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 4h12l4 4v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z" stroke-linejoin="round"/><path d="M8 9h8M8 13h8M8 17h5" stroke-linecap="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-semibold text-navy-dark">No orders yet</h3>
            <p class="mt-1 text-sm text-gray-500">When customers check out in the shop, their orders appear here.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Order</th>
                            <th class="px-5 py-3 font-semibold">Customer</th>
                            <th class="px-5 py-3 font-semibold">Items</th>
                            <th class="px-5 py-3 font-semibold">Total</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($orders as $o)
                            @php $count = collect($o->items ?? [])->sum('qty'); @endphp
                            <tr class="nf-adm-row transition hover:bg-gray-50/70">
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-navy-dark">{{ $o->reference }}</p>
                                    <p class="text-xs text-gray-400">{{ $o->created_at?->format('d M Y · g:i a') }}</p>
                                </td>
                                <td class="px-5 py-3">
                                    <p class="font-medium text-navy-dark">{{ $o->name }}</p>
                                    <a href="mailto:{{ $o->email }}" class="text-xs text-gray-400 hover:text-brand">{{ $o->email }}</a>
                                </td>
                                <td class="px-5 py-3 text-gray-600">{{ $count }} {{ \Illuminate\Support\Str::plural('item', $count) }}</td>
                                <td class="px-5 py-3 font-semibold text-navy-dark">{{ $o->currencySymbol() }}{{ number_format($o->subtotal, 2) }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold capitalize {{ $statusStyles[$o->status] ?? 'bg-gray-100 text-gray-500' }}">
                                        {{ $o->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" data-view
                                                data-ref="{{ $o->reference }}"
                                                data-name="{{ $o->name }}"
                                                data-email="{{ $o->email }}"
                                                data-phone="{{ $o->phone }}"
                                                data-address="{{ $o->address }}"
                                                data-total="{{ $o->currencySymbol() }}{{ number_format($o->subtotal, 2) }}"
                                                data-status="{{ $o->status }}"
                                                data-status-action="{{ route('admin.orders.status', $o) }}"
                                                data-items="{{ json_encode($o->items ?? []) }}"
                                                data-date="{{ $o->created_at?->format('d M Y · g:i a') }}"
                                                class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 px-3 py-1.5 text-xs font-semibold text-navy transition hover:border-brand hover:text-brand">
                                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                            View
                                        </button>
                                        <button type="button" data-delete
                                                data-action="{{ route('admin.orders.destroy', $o) }}"
                                                data-name="{{ $o->reference }}"
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

        @if ($orders->hasPages())
            <div class="mt-5">{{ $orders->links() }}</div>
        @endif
    @endif

    {{-- ===== View order modal ===== --}}
    <div class="nf-modal" data-view-modal hidden>
        <div class="nf-modal__backdrop" data-modal-close></div>
        <div class="nf-modal__card nf-modal__card--split" role="dialog" aria-modal="true" aria-label="Order details" style="max-width:40rem;">
            <button type="button" class="nf-modal__close" data-modal-close aria-label="Close">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round"/></svg>
            </button>

            <div class="p-6 sm:p-7">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-brand">Order</p>
                        <h3 class="text-lg font-bold text-navy-dark" data-f-ref></h3>
                        <p class="text-xs text-gray-400" data-f-date></p>
                    </div>
                    <p class="text-2xl font-extrabold text-navy-dark" data-f-total></p>
                </div>

                {{-- Items --}}
                <div class="mt-5 rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-400">Items</p>
                    <ul class="space-y-1.5 text-sm" data-f-items></ul>
                </div>

                {{-- Customer --}}
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Customer</p>
                        <p class="mt-1 font-semibold text-navy-dark" data-f-name></p>
                        <a class="block text-sm text-brand hover:underline" data-f-email></a>
                        <a class="block text-sm text-brand hover:underline" data-f-phone></a>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Delivery address</p>
                        <p class="mt-1 text-sm leading-relaxed text-navy-dark" data-f-address></p>
                    </div>
                </div>

                {{-- Status --}}
                <form method="POST" data-status-form class="mt-6 flex items-end gap-3 border-t border-gray-100 pt-5">
                    @csrf
                    @method('PATCH')
                    <div class="flex-1">
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-gray-400">Order status</label>
                        <select name="status" data-status-select
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm font-medium text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/25">
                            @foreach ($statuses as $s)
                                <option value="{{ $s }}">{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-brand h-11 px-5">Update</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Delete modal ===== --}}
    <div class="nf-modal" data-delete-modal hidden>
        <div class="nf-modal__backdrop" data-modal-close></div>
        <div class="nf-modal__card text-center" role="dialog" aria-modal="true" aria-label="Delete order">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-red-50 text-red-600">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <h3 class="mt-4 text-lg font-bold text-navy-dark">Delete order?</h3>
            <p class="mt-1.5 text-sm text-gray-500">
                This will permanently remove order <span class="font-semibold text-navy-dark" data-del-name></span>. This cannot be undone.
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
    const statusForm = viewModal?.querySelector('[data-status-form]');
    const modals = [viewModal, delModal].filter(Boolean);

    const open = (m) => { m.hidden = false; requestAnimationFrame(() => m.classList.add('is-open')); };
    const close = (m) => { m.classList.remove('is-open'); setTimeout(() => { m.hidden = true; }, 320); };
    const money = (n) => '£' + (Number(n) || 0).toFixed(2);

    document.querySelectorAll('[data-view]').forEach((btn) => btn.addEventListener('click', () => {
        const d = btn.dataset;
        viewModal.querySelector('[data-f-ref]').textContent = d.ref;
        viewModal.querySelector('[data-f-date]').textContent = d.date;
        viewModal.querySelector('[data-f-total]').textContent = d.total;
        viewModal.querySelector('[data-f-name]').textContent = d.name;
        const email = viewModal.querySelector('[data-f-email]');
        email.textContent = d.email; email.href = 'mailto:' + d.email;
        const phone = viewModal.querySelector('[data-f-phone]');
        phone.textContent = d.phone; phone.href = 'tel:' + d.phone;
        viewModal.querySelector('[data-f-address]').textContent = d.address;

        const list = viewModal.querySelector('[data-f-items]');
        list.innerHTML = '';
        let items = [];
        try { items = JSON.parse(d.items || '[]'); } catch (e) {}
        items.forEach((it) => {
            const li = document.createElement('li');
            li.className = 'flex justify-between gap-3';
            const left = document.createElement('span');
            left.className = 'text-navy-dark';
            left.textContent = (it.qty || 1) + '× ' + (it.name || '');
            const right = document.createElement('span');
            right.className = 'font-semibold text-navy-dark';
            right.textContent = money(it.line != null ? it.line : (it.price || 0) * (it.qty || 1));
            li.append(left, right);
            list.appendChild(li);
        });

        if (statusForm) {
            statusForm.action = d.statusAction;
            const sel = statusForm.querySelector('[data-status-select]');
            if (sel) sel.value = d.status;
        }

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

    document.querySelectorAll('.nf-adm-row').forEach((row, i) => {
        setTimeout(() => row.classList.add('in'), 40 + i * 45);
    });
})();
</script>
@endpush
