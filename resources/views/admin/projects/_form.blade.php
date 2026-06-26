{{-- Shared fields for create/edit. Expects $project (Project, possibly unsaved). --}}
<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main fields --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Project content</h3>

            <div class="space-y-5">
                <div>
                    <label for="title" class="mb-1.5 block text-sm font-semibold text-navy-dark">Title <span class="text-red-500">*</span></label>
                    <input id="title" name="title" type="text" value="{{ old('title', $project->title) }}" required
                           placeholder="Binoria Water Campaign"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-semibold text-navy-dark">Description <span class="text-red-500">*</span></label>
                    <textarea id="description" name="description" rows="2" required maxlength="255"
                              placeholder="Water Crisis Hit Jamia Binoria Hard…"
                              class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">{{ old('description', $project->description) }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Short line shown under the title on the card (max 255 characters).</p>
                </div>

                <div>
                    <label for="link" class="mb-1.5 block text-sm font-semibold text-navy-dark">Donate link <span class="text-gray-400">(optional)</span></label>
                    <input id="link" name="link" type="text" value="{{ old('link', $project->link) }}"
                           placeholder="https://… or /donate"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <p class="mt-1 text-xs text-gray-400">Where the "Donate Now" button links. Leave empty for none.</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Image</h3>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                <div class="shrink-0">
                    <div class="relative h-28 w-44 overflow-hidden rounded-lg bg-gray-100 ring-1 ring-gray-200">
                        <img data-image-preview
                             src="{{ $project->image ? asset($project->image) : '' }}"
                             alt="Preview"
                             class="h-full w-full object-cover {{ $project->image ? '' : 'hidden' }}">
                        <span data-image-placeholder class="absolute inset-0 grid place-items-center text-gray-300 {{ $project->image ? 'hidden' : '' }}">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 15l5-4 4 3 3-2 6 5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </div>
                </div>
                <div class="flex-1">
                    <label for="image" class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center transition hover:border-brand hover:bg-cream/40">
                        <svg class="h-6 w-6 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="mt-2 text-sm font-semibold text-navy-dark">Click to upload an image</span>
                        <span class="mt-0.5 text-xs text-gray-400">JPG, PNG or WEBP — up to 4 MB</span>
                        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" data-image-input class="sr-only">
                    </label>
                    @if ($project->exists)
                        <p class="mt-2 text-xs text-gray-400">Leave empty to keep the current image.</p>
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
                    <input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $project->sort_order) }}" placeholder="Auto"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <p class="mt-1 text-xs text-gray-400">Leave empty to place it automatically after the last one (a gap from a deleted project is filled first). Lower numbers show first.</p>
                </div>

                <div>
                    <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Visibility</span>
                    <label class="flex cursor-pointer items-center justify-between rounded-lg border border-gray-200 px-3.5 py-3">
                        <span class="text-sm text-gray-600">Show on website</span>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $project->is_active ?? true) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-gray-300 text-brand focus:ring-brand">
                    </label>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3">
                <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-brand text-sm font-semibold text-white transition hover:bg-brand-dark">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ $project->exists ? 'Save Changes' : 'Create Project' }}
                </button>
                <a href="{{ route('admin.projects.index') }}" class="flex h-11 w-full items-center justify-center rounded-lg border border-gray-200 text-sm font-semibold text-navy transition hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const input = document.querySelector('[data-image-input]');
        const preview = document.querySelector('[data-image-preview]');
        const placeholder = document.querySelector('[data-image-placeholder]');
        if (!input) return;
        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            if (preview) { preview.src = url; preview.classList.remove('hidden'); }
            if (placeholder) placeholder.classList.add('hidden');
        });
    })();
</script>
@endpush
