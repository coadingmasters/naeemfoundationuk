{{-- Shared fields for create/edit. Expects $orphan (Orphan, possibly unsaved). --}}
<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main fields --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Orphan details</h3>

            <div class="space-y-5">
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-navy-dark">Name <span class="text-red-500">*</span></label>
                    <input id="name" name="name" type="text" value="{{ old('name', $orphan->name) }}" required
                           placeholder="Uzair Afzal"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="location" class="mb-1.5 block text-sm font-semibold text-navy-dark">Location</label>
                        <input id="location" name="location" type="text" value="{{ old('location', $orphan->location) }}"
                               placeholder="Karachi"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    </div>
                    <div>
                        <label for="grade" class="mb-1.5 block text-sm font-semibold text-navy-dark">Grade / Class</label>
                        <input id="grade" name="grade" type="text" value="{{ old('grade', $orphan->grade) }}"
                               placeholder="Hifz"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="dob" class="mb-1.5 block text-sm font-semibold text-navy-dark">Date of birth <span class="text-gray-400">(optional)</span></label>
                        <input id="dob" name="dob" type="text" value="{{ old('dob', $orphan->dob) }}"
                               placeholder="12 March 2010"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    </div>
                    <div>
                        <label for="monthly_amount" class="mb-1.5 block text-sm font-semibold text-navy-dark">Suggested monthly amount <span class="text-gray-400">(optional)</span></label>
                        <input id="monthly_amount" name="monthly_amount" type="number" min="1" max="1000000" value="{{ old('monthly_amount', $orphan->monthly_amount) }}"
                               placeholder="e.g. 30"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                        <p class="mt-1 text-xs text-gray-400">Pre-fills the sponsorship amount on this orphan's page.</p>
                    </div>
                </div>

                <div>
                    <label for="story" class="mb-1.5 block text-sm font-semibold text-navy-dark">Story <span class="text-gray-400">(optional)</span></label>
                    <textarea id="story" name="story" rows="4" maxlength="2000"
                              placeholder="A short background about this child, shown on their profile page…"
                              class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">{{ old('story', $orphan->story) }}</textarea>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Photo</h3>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                <div class="shrink-0">
                    <div class="relative h-36 w-28 overflow-hidden rounded-lg bg-gray-100 ring-1 ring-gray-200">
                        <img data-image-preview
                             src="{{ $orphan->photo ? asset($orphan->photo) : '' }}"
                             alt="Preview"
                             class="h-full w-full object-cover {{ $orphan->photo ? '' : 'hidden' }}">
                        <span data-image-placeholder class="absolute inset-0 grid place-items-center text-gray-300 {{ $orphan->photo ? 'hidden' : '' }}">
                            <svg class="h-10 w-10" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5zm0 2c-4 0-8 2-8 5v1h16v-1c0-3-4-5-8-5z"/></svg>
                        </span>
                    </div>
                </div>
                <div class="flex-1">
                    <label for="photo" class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center transition hover:border-brand hover:bg-cream/40">
                        <svg class="h-6 w-6 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="mt-2 text-sm font-semibold text-navy-dark">Click to upload a photo</span>
                        <span class="mt-0.5 text-xs text-gray-400">JPG, PNG or WEBP — up to 4 MB</span>
                        <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" data-image-input class="sr-only">
                    </label>
                    <p class="mt-2 text-xs text-gray-400">Optional — a clean avatar is shown when no photo is set.@if ($orphan->exists) Leave empty to keep the current photo.@endif</p>
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
                    <input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $orphan->sort_order) }}" placeholder="Auto"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <p class="mt-1 text-xs text-gray-400">Leave empty to place it after the last one. Lower numbers show first.</p>
                </div>

                <div>
                    <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Visibility</span>
                    <label class="flex cursor-pointer items-center justify-between rounded-lg border border-gray-200 px-3.5 py-3">
                        <span class="text-sm text-gray-600">Show on website</span>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $orphan->is_active ?? true) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-gray-300 text-brand focus:ring-brand">
                    </label>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3">
                <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-brand text-sm font-semibold text-white transition hover:bg-brand-dark">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ $orphan->exists ? 'Save Changes' : 'Add Orphan' }}
                </button>
                <a href="{{ route('admin.orphans.index') }}" class="flex h-11 w-full items-center justify-center rounded-lg border border-gray-200 text-sm font-semibold text-navy transition hover:bg-gray-50">
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
