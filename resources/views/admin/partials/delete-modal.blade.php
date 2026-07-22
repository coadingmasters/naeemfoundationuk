{{--
    Reusable delete-confirmation modal for admin resource lists.
    Trigger it from any button that sits inside its own DELETE <form>:
        <button type="button" data-admin-delete data-label="Item name" ...>Delete</button>
    The modal submits that button's enclosing form when confirmed.
--}}
<div class="nf-modal" data-admin-del-modal hidden>
    <div class="nf-modal__backdrop" data-admin-del-close></div>
    <div class="nf-modal__card text-center" role="dialog" aria-modal="true" aria-label="Confirm delete">
        <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-red-50 text-red-600">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0v12a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7M10 11v6M14 11v6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <h3 class="mt-4 text-lg font-bold text-navy-dark">Delete this item?</h3>
        <p class="mt-1.5 text-sm text-gray-500">
            You’re about to permanently delete
            <span class="font-semibold text-navy-dark" data-admin-del-label>this item</span>.
            This action cannot be undone.
        </p>

        <div class="mt-6 flex gap-3">
            <button type="button" data-admin-del-close class="flex-1 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-navy transition hover:bg-gray-50">Cancel</button>
            <button type="button" data-admin-del-confirm class="flex-1 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">Delete</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const modal = document.querySelector('[data-admin-del-modal]');
    if (!modal) return;

    const label = modal.querySelector('[data-admin-del-label]');
    const confirmBtn = modal.querySelector('[data-admin-del-confirm]');
    let targetForm = null;

    const open = () => { modal.hidden = false; requestAnimationFrame(() => modal.classList.add('is-open')); };
    const close = () => { modal.classList.remove('is-open'); setTimeout(() => { modal.hidden = true; }, 320); targetForm = null; };

    document.querySelectorAll('[data-admin-delete]').forEach((btn) => btn.addEventListener('click', (e) => {
        e.preventDefault();
        targetForm = btn.closest('form');
        label.textContent = btn.dataset.label ? '“' + btn.dataset.label + '”' : 'this item';
        open();
    }));

    confirmBtn.addEventListener('click', () => {
        if (targetForm) targetForm.submit();
    });

    modal.querySelectorAll('[data-admin-del-close]').forEach((el) => el.addEventListener('click', close));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.hidden) close(); });
})();
</script>
@endpush
