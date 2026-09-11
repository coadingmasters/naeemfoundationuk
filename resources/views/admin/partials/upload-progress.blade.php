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
    <div class="mt-2 hidden rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700" data-upload-error></div>
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

            const errorBox = form.querySelector('[data-upload-error]');
            const submitBtn = form.querySelector('button[type="submit"]');
            const submitBtnOriginalHtml = submitBtn ? submitBtn.innerHTML : '';

            const setProgress = (text, percent) => {
                pctLabel.textContent = text;
                if (percent !== null) fill.style.width = percent + '%';
                if (submitBtn) submitBtn.textContent = text;
            };

            const setFailed = (text) => {
                pctLabel.textContent = 'Upload failed';
                fill.classList.remove('bg-brand');
                fill.classList.add('bg-red-500');
                if (errorBox) {
                    errorBox.textContent = text;
                    errorBox.classList.remove('hidden');
                    errorBox.scrollIntoView({behavior: 'smooth', block: 'nearest'});
                }
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
                // Ask for JSON back. Without this the server answers with a
                // redirect, which XHR follows transparently — silently eating
                // the flashed success/validation messages before the browser
                // ever renders them, so a rejected upload looked like nothing
                // happened at all.
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Accept', 'application/json');
                // Generous ceiling for a large file on a slow connection — just
                // under the server's own 1200s max_execution_time/max_input_time
                // (public/.htaccess), so a genuinely slow-but-progressing upload
                // isn't cut off client-side before the server would allow it.
                xhr.timeout = 1100000;

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
                    let payload = null;
                    try { payload = JSON.parse(xhr.responseText); } catch (err) { /* not JSON */ }

                    if (xhr.status >= 200 && xhr.status < 300) {
                        // Saved. The success message is flashed server-side and
                        // shows on the page we're about to land on.
                        setProgress('Saved — loading…', 100);
                        window.location.href = (payload && payload.redirect) || xhr.responseURL || form.action;
                        return;
                    }

                    if (xhr.status === 422 && payload && payload.errors) {
                        // Validation rejected it — show exactly why, rather
                        // than leaving the admin guessing.
                        const messages = Object.values(payload.errors).flat();
                        setFailed(messages.join(' '));
                        return;
                    }

                    if (xhr.status === 413) {
                        setFailed('That file is too large for the server to accept.');
                        return;
                    }

                    setFailed('Upload failed (server error ' + xhr.status + ') — please try again.');
                });

                xhr.addEventListener('error', () => setFailed('Upload failed — check your connection and try again.'));
                xhr.addEventListener('timeout', () => setFailed('Upload timed out — try again, or use a smaller file.'));

                bar.classList.remove('hidden');
                bar.scrollIntoView({behavior: 'smooth', block: 'nearest'});
                fill.classList.remove('bg-red-500');
                fill.classList.add('bg-brand');
                if (errorBox) errorBox.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = true;
                setProgress('Uploading… 0%', 0);

                xhr.send(formData);
            });
        });
    })();
</script>
@endpush
