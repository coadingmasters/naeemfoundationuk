{{-- Shared fields for create/edit. Expects $post (NewsPost, possibly unsaved). --}}
<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Story</h3>

            <div class="space-y-5">
                <div>
                    <label for="title" class="mb-1.5 block text-sm font-semibold text-navy-dark">Headline <span class="text-red-500">*</span></label>
                    <input id="title" name="title" type="text" value="{{ old('title', $post->title) }}" required
                           placeholder="Civilians fleeing conflict urgently need aid"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                </div>

                <div>
                    <label for="excerpt" class="mb-1.5 block text-sm font-semibold text-navy-dark">Standfirst <span class="text-gray-400">(optional)</span></label>
                    <textarea id="excerpt" name="excerpt" rows="2"
                              placeholder="One or two lines shown on the cards and at the top of the article."
                              class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>

                <div>
                    <label for="body" class="mb-1.5 block text-sm font-semibold text-navy-dark">Article</label>
                    <textarea id="body" name="body" rows="14"
                              placeholder="Write the full story here. Leave a blank line between paragraphs."
                              class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm leading-relaxed text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">{{ old('body', $post->body) }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Plain text. Leave a blank line between paragraphs — they're formatted automatically.</p>
                </div>

                <div>
                    <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Main image</span>
                    <label for="image_file" class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center transition hover:border-brand hover:bg-cream/40">
                        <svg class="h-6 w-6 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 16l4-4 4 4 3-3 5 5M4 6h16v12H4z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="mt-2 text-sm font-semibold text-navy-dark" data-img-label>Click to upload an image</span>
                        <span class="mt-0.5 text-xs text-gray-400">JPG or PNG — up to 6 MB</span>
                        <input id="image_file" name="image_file" type="file" accept="image/*" data-img-input class="sr-only">
                    </label>
                    @if ($post->exists && $post->image_url)
                        <img src="{{ $post->image_url }}" alt="" class="mt-3 h-28 w-48 rounded-lg object-cover ring-1 ring-gray-200">
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Settings --}}
    <div class="space-y-5">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Publishing</h3>

            <div class="space-y-5">
                <div>
                    <label for="category" class="mb-1.5 block text-sm font-semibold text-navy-dark">Category <span class="text-red-500">*</span></label>
                    <select id="category" name="category" required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                        @foreach (\App\Models\NewsPost::CATEGORIES as $c)
                            <option value="{{ $c }}" @selected(old('category', $post->category) === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="published_at" class="mb-1.5 block text-sm font-semibold text-navy-dark">Publish date</label>
                    <input id="published_at" name="published_at" type="date"
                           value="{{ old('published_at', optional($post->published_at)->format('Y-m-d')) }}"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                </div>

                <div>
                    <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Options</span>
                    <label class="flex cursor-pointer items-center justify-between rounded-lg border border-gray-200 px-3.5 py-3">
                        <span class="text-sm text-gray-600">Show on website</span>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $post->is_active ?? true) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-gray-300 text-brand focus:ring-brand">
                    </label>
                    <label class="mt-2 flex cursor-pointer items-center justify-between rounded-lg border border-gray-200 px-3.5 py-3">
                        <span class="text-sm text-gray-600">Featured lead story</span>
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-gray-300 text-brand focus:ring-brand">
                    </label>
                    <p class="mt-1 text-xs text-gray-400">Only one story can be featured — setting this unsets the others.</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3">
                <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-brand text-sm font-semibold text-white transition hover:bg-brand-dark">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ $post->exists ? 'Save Changes' : 'Publish Story' }}
                </button>
                <a href="{{ route('admin.news.index') }}" class="flex h-11 w-full items-center justify-center rounded-lg border border-gray-200 text-sm font-semibold text-navy transition hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const input = document.querySelector('[data-img-input]');
        const label = document.querySelector('[data-img-label]');
        if (!input || !label) return;
        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            label.textContent = file ? file.name : 'Click to upload an image';
        });
    })();
</script>
@endpush
