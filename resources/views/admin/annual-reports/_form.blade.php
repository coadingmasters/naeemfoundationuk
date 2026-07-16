{{-- Shared fields for create/edit. Expects $report (AnnualReport, possibly unsaved). --}}
<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main fields --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Report details</h3>

            <div class="space-y-5">
                <div class="grid gap-5 sm:grid-cols-3">
                    <div class="sm:col-span-2">
                        <label for="title" class="mb-1.5 block text-sm font-semibold text-navy-dark">Title <span class="text-red-500">*</span></label>
                        <input id="title" name="title" type="text" value="{{ old('title', $report->title) }}" required
                               placeholder="Annual Report 2024"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    </div>
                    <div>
                        <label for="year" class="mb-1.5 block text-sm font-semibold text-navy-dark">Year <span class="text-red-500">*</span></label>
                        <input id="year" name="year" type="text" value="{{ old('year', $report->year) }}" required
                               placeholder="2024"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    </div>
                </div>

                <div>
                    <label for="summary" class="mb-1.5 block text-sm font-semibold text-navy-dark">Summary <span class="text-gray-400">(optional)</span></label>
                    <textarea id="summary" name="summary" rows="3"
                              placeholder="A short line about what this report covers."
                              class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">{{ old('summary', $report->summary) }}</textarea>
                </div>

                {{-- PDF --}}
                <div>
                    <span class="mb-1.5 block text-sm font-semibold text-navy-dark">
                        PDF file @unless ($report->exists) <span class="text-red-500">*</span> @endunless
                    </span>
                    <label for="pdf_file" class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center transition hover:border-brand hover:bg-cream/40">
                        <svg class="h-6 w-6 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="mt-2 text-sm font-semibold text-navy-dark" data-pdf-label>Click to upload the report PDF</span>
                        <span class="mt-0.5 text-xs text-gray-400">PDF only — up to 20 MB</span>
                        <input id="pdf_file" name="pdf_file" type="file" accept="application/pdf" data-pdf-input class="sr-only">
                    </label>
                    @if ($report->exists && $report->file_path)
                        <p class="mt-2 text-xs text-gray-400">
                            Current file:
                            <a href="{{ $report->file_url }}" target="_blank" rel="noopener" class="font-semibold text-brand underline">{{ basename($report->file_path) }}</a>
                            — upload a new one to replace it.
                        </p>
                    @endif
                </div>

                {{-- Cover --}}
                <div>
                    <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Cover image <span class="text-gray-400">(optional)</span></span>
                    <label for="cover_file" class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center transition hover:border-brand hover:bg-cream/40">
                        <svg class="h-6 w-6 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 16l4-4 4 4 3-3 5 5M4 6h16v12H4z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="mt-2 text-sm font-semibold text-navy-dark" data-cover-label>Click to upload a cover</span>
                        <span class="mt-0.5 text-xs text-gray-400">JPG or PNG — a smart default is shown if you skip this</span>
                        <input id="cover_file" name="cover_file" type="file" accept="image/*" data-cover-input class="sr-only">
                    </label>
                    @if ($report->exists && $report->cover_url)
                        <img src="{{ $report->cover_url }}" alt="" class="mt-3 h-24 w-40 rounded-lg object-cover ring-1 ring-gray-200">
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Settings --}}
    <div class="space-y-5">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Settings</h3>

            <div class="space-y-5">
                <div>
                    <label for="sort_order" class="mb-1.5 block text-sm font-semibold text-navy-dark">Display order <span class="text-gray-400">(optional)</span></label>
                    <input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $report->sort_order) }}" placeholder="Auto"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <p class="mt-1 text-xs text-gray-400">Leave empty to place it automatically. Lower numbers show first.</p>
                </div>

                <div>
                    <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Visibility</span>
                    <label class="flex cursor-pointer items-center justify-between rounded-lg border border-gray-200 px-3.5 py-3">
                        <span class="text-sm text-gray-600">Show on website</span>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $report->is_active ?? true) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-gray-300 text-brand focus:ring-brand">
                    </label>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3">
                <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-brand text-sm font-semibold text-white transition hover:bg-brand-dark">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ $report->exists ? 'Save Changes' : 'Add Report' }}
                </button>
                <a href="{{ route('admin.annual-reports.index') }}" class="flex h-11 w-full items-center justify-center rounded-lg border border-gray-200 text-sm font-semibold text-navy transition hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const pairs = [
            ['[data-pdf-input]', '[data-pdf-label]', 'Click to upload the report PDF'],
            ['[data-cover-input]', '[data-cover-label]', 'Click to upload a cover'],
        ];
        pairs.forEach(([inputSel, labelSel, fallback]) => {
            const input = document.querySelector(inputSel);
            const label = document.querySelector(labelSel);
            if (!input || !label) return;
            input.addEventListener('change', () => {
                const file = input.files && input.files[0];
                label.textContent = file ? file.name : fallback;
            });
        });
    })();
</script>
@endpush
