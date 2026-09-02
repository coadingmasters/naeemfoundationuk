{{-- Shared fields for the hero-banner add/edit forms.
     Expects:
       $hero      — PageHero (possibly unsaved)
       $mode      — 'create' | 'edit'
     create mode also gets:  $pages (key => label), $pageKey (old value)
     edit mode also gets:    $pageKey, $pageLabel
--}}
@php $isEdit = ($mode ?? 'create') === 'edit'; @endphp

<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main fields --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Banner photo</h3>

            <div class="space-y-5">
                {{-- Which page --}}
                <div>
                    <label for="page_key" class="mb-1.5 block text-sm font-semibold text-navy-dark">Page <span class="text-red-500">*</span></label>
                    @if ($isEdit)
                        <div class="flex h-11 w-full items-center rounded-lg border border-gray-200 bg-gray-50 px-3.5 text-sm font-semibold text-navy-dark">
                            {{ $pageLabel }}
                        </div>
                    @else
                        <select id="page_key" name="page_key" required
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                            <option value="">Choose a page…</option>
                            @foreach ($pages as $key => $label)
                                <option value="{{ $key }}" @selected(old('page_key', $pageKey) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-400">Choosing a page that already has a custom banner will replace it.</p>
                    @endif
                </div>

                @if ($isEdit && $hero->exists)
                    <div>
                        <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Current banner</span>
                        <img src="{{ asset($hero->image) }}" alt="Current hero banner"
                             class="h-32 w-full rounded-lg border border-gray-200 object-cover">
                    </div>
                @endif

                <div>
                    <label for="image" class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-8 text-center transition hover:border-brand hover:bg-cream/40">
                        <svg class="h-6 w-6 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="mt-2 text-sm font-semibold text-navy-dark" data-file-label>{{ $isEdit && $hero->exists ? 'Click to upload a replacement photo' : 'Click to upload a photo' }}</span>
                        <span class="mt-0.5 text-xs text-gray-400">JPG, PNG or WEBP — any size, resized automatically</span>
                        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp" data-file-input class="sr-only" {{ $isEdit && $hero->exists ? '' : 'required' }}>
                    </label>
                    <p class="mt-1 text-xs text-gray-400">Upload straight from your phone or camera — it's automatically resized to a web-friendly size, no editing needed.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Settings --}}
    <div class="space-y-5">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Settings</h3>

            <div>
                <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Visibility</span>
                <label class="flex cursor-pointer items-center justify-between rounded-lg border border-gray-200 px-3.5 py-3">
                    <span class="text-sm text-gray-600">Show on the website</span>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $hero->is_active ?? true) ? 'checked' : '' }}
                           class="h-5 w-5 rounded border-gray-300 text-brand focus:ring-brand">
                </label>
                <p class="mt-1 text-xs text-gray-400">When hidden, the page falls back to its default banner.</p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3">
                <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-brand text-sm font-semibold text-white transition hover:bg-brand-dark">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ $hero->exists ? 'Save Changes' : 'Set Banner' }}
                </button>
                <a href="{{ route('admin.page-heroes.index') }}" class="flex h-11 w-full items-center justify-center rounded-lg border border-gray-200 text-sm font-semibold text-navy transition hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const input = document.querySelector('[data-file-input]');
        const label = document.querySelector('[data-file-label]');
        if (!input || !label) return;
        const original = label.textContent;
        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            label.textContent = file ? file.name : original;
        });
    })();
</script>
@endpush
