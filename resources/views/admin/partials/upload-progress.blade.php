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

    Feedback shows in two places: the submit button itself (often in a
    separate column from this bar on desktop, so it's what the admin is
    actually looking at right after they click it) and this dedicated bar,
    which also scrolls into view so it's never missed.
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

            const submitBtn = form.querySelector('button[type="submit"]');
            const submitBtnOriginalHtml = submitBtn ? submitBtn.innerHTML : '';

            const setProgress = (text, percent) => {
                pctLabel.textContent = text;
                if (percent !== null) fill.style.width = percent + '%';
                if (submitBtn) submitBtn.textContent = text;
            };

            const setFailed = (text) => {
                pctLabel.textContent = text;
                fill.classList.remove('bg-brand');
                fill.classList.add('bg-red-500');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = submitBtnOriginalHtml;
                }
            };

            form.addEventListener('submit', (e) => {
                // Only take over the request when a file was actually chosen —
                // a text-only edit (no new upload) submits the normal way.
                if (!fileInput.files || !fileInput.files.length) return;

                e.preventDefault();

                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();
                xhr.open(form.getAttribute('method') || 'POST', form.action, true);
                // Generous ceiling for a large file on a slow connection —
                // matches the server's own max_execution_time.
                xhr.timeout = 280000;

                xhr.upload.addEventListener('progress', (evt) => {
                    if (!evt.lengthComputable) return;
                    setProgress('Uploading… ' + Math.round((evt.loaded / evt.total) * 100) + '%', Math.round((evt.loaded / evt.total) * 100));
                });

                xhr.upload.addEventListener('load', () => {
                    // The browser finished sending the file — the server still
                    // needs a moment to store it and write the database row.
                    setProgress('Upload complete — saving…', 100);
                });

                xhr.addEventListener('load', () => {
                    // The server always responds with a redirect (to the index
                    // on success, or back to this form with errors on
                    // validation failure) — XHR follows it transparently, so
                    // just send the browser to wherever it ended up.
                    if (xhr.status >= 200 && xhr.status < 400) {
                        window.location.href = xhr.responseURL || form.action;
                    } else {
                        setFailed('Upload failed (server error) — please try again.');
                    }
                });

                xhr.addEventListener('error', () => setFailed('Upload failed — check your connection and try again.'));
                xhr.addEventListener('timeout', () => setFailed('Upload timed out — try again, or use a smaller file.'));

                bar.classList.remove('hidden');
                bar.scrollIntoView({behavior: 'smooth', block: 'nearest'});
                if (submitBtn) submitBtn.disabled = true;
                setProgress('Uploading… 0%', 0);

                xhr.send(formData);
            });
        });
    })();
</script>
@endpush
