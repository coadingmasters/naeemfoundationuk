{{--
    Upload progress bar for large-file forms. Include once inside any form that
    has a [data-file-input] file field — this renders the bar markup AND wires
    up the submit handler for every such form on the page.

    On submit, if a file was actually chosen, the form is sent via XHR instead
    of a normal browser submit so we get real upload-progress events (video
    files can run into the hundreds of MB and take a while over a real
    connection). The server's response is unchanged — still a normal redirect
    with a flash message on success, or back-with-errors on validation
    failure — so once the browser has the file fully uploaded we just navigate
    to wherever the server redirected, and the existing flash/error UI takes
    over exactly as it would for a non-JS submit.
--}}
<div class="mt-3 hidden" data-upload-progress>
    <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
        <div class="h-full w-0 rounded-full bg-brand transition-[width] duration-150 ease-out" data-upload-progress-fill></div>
    </div>
    <p class="mt-1.5 text-xs font-semibold text-navy-dark" data-upload-progress-pct>Uploading… 0%</p>
</div>

@push('scripts')
<script>
    (function () {
        document.querySelectorAll('form').forEach((form) => {
            const fileInput = form.querySelector('[data-file-input]');
            const bar = form.querySelector('[data-upload-progress]');
            const fill = form.querySelector('[data-upload-progress-fill]');
            const pctLabel = form.querySelector('[data-upload-progress-pct]');
            if (!fileInput || !bar || !fill || !pctLabel) return;

            form.addEventListener('submit', (e) => {
                // Only take over the request when a file was actually chosen —
                // a text-only edit (no new upload) submits the normal way.
                if (!fileInput.files || !fileInput.files.length) return;

                e.preventDefault();

                const submitBtn = form.querySelector('button[type="submit"]');
                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();
                xhr.open(form.getAttribute('method') || 'POST', form.action, true);

                xhr.upload.addEventListener('progress', (evt) => {
                    if (!evt.lengthComputable) return;
                    const percent = Math.round((evt.loaded / evt.total) * 100);
                    fill.style.width = percent + '%';
                    pctLabel.textContent = 'Uploading… ' + percent + '%';
                });

                xhr.upload.addEventListener('load', () => {
                    // The browser finished sending the file — the server still
                    // needs a moment to store it and write the database row.
                    fill.style.width = '100%';
                    pctLabel.textContent = 'Upload complete — saving…';
                });

                xhr.addEventListener('load', () => {
                    // The server always responds with a redirect (to the index
                    // on success, or back to this form with errors on
                    // validation failure) — XHR follows it transparently, so
                    // just send the browser to wherever it ended up.
                    if (xhr.status >= 200 && xhr.status < 400) {
                        window.location.href = xhr.responseURL || form.action;
                    } else {
                        pctLabel.textContent = 'Upload failed — please try again.';
                        fill.classList.remove('bg-brand');
                        fill.classList.add('bg-red-500');
                        if (submitBtn) submitBtn.disabled = false;
                    }
                });

                xhr.addEventListener('error', () => {
                    pctLabel.textContent = 'Upload failed — check your connection and try again.';
                    fill.classList.remove('bg-brand');
                    fill.classList.add('bg-red-500');
                    if (submitBtn) submitBtn.disabled = false;
                });

                bar.hidden = false;
                fill.style.width = '0%';
                pctLabel.textContent = 'Uploading… 0%';
                if (submitBtn) submitBtn.disabled = true;

                xhr.send(formData);
            });
        });
    })();
</script>
@endpush
