{{-- Shared fields for create/edit. Expects $product (Product, possibly unsaved). --}}
<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main fields --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Product details</h3>

            <div class="space-y-5">
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-navy-dark">Name <span class="text-red-500">*</span></label>
                    <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required
                           placeholder="Handwoven Prayer Mat"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-semibold text-navy-dark">Description</label>
                    <textarea id="description" name="description" rows="5" maxlength="2000"
                              placeholder="Describe the product…"
                              class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="category" class="mb-1.5 block text-sm font-semibold text-navy-dark">Category <span class="text-red-500">*</span></label>
                        <select id="category" name="category" required
                                class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                            @foreach (\App\Models\Product::CATEGORIES as $cat)
                                <option value="{{ $cat }}" @selected(old('category', $product->category) === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="price" class="mb-1.5 block text-sm font-semibold text-navy-dark">Price — UK (£) <span class="text-red-500">*</span></label>
                        <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price) }}" required
                               placeholder="19.99"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    </div>
                    <div>
                        <label for="price_usd" class="mb-1.5 block text-sm font-semibold text-navy-dark">Price — US ($) <span class="text-gray-400">(optional)</span></label>
                        <input id="price_usd" name="price_usd" type="number" step="0.01" min="0" value="{{ old('price_usd', $product->price_usd) }}"
                               placeholder="Same as UK if empty"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    </div>
                    <div>
                        <label for="price_cad" class="mb-1.5 block text-sm font-semibold text-navy-dark">Price — Canada (CA$) <span class="text-gray-400">(optional)</span></label>
                        <input id="price_cad" name="price_cad" type="number" step="0.01" min="0" value="{{ old('price_cad', $product->price_cad) }}"
                               placeholder="Same as UK if empty"
                               class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    </div>
                </div>

                <div>
                    <label for="badge" class="mb-1.5 block text-sm font-semibold text-navy-dark">Badge <span class="text-gray-400">(optional)</span></label>
                    <input id="badge" name="badge" type="text" value="{{ old('badge', $product->badge) }}" maxlength="40"
                           placeholder="New / Sale / Bestseller"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <p class="mt-1 text-xs text-gray-400">A small label shown on the product image. Leave empty for none.</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Image @unless ($product->exists)<span class="text-red-500">*</span>@endunless</h3>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                <div class="shrink-0">
                    <div class="relative h-32 w-32 overflow-hidden rounded-lg bg-gray-100 ring-1 ring-gray-200">
                        <img data-image-preview
                             src="{{ $product->image ? asset($product->image) : '' }}"
                             alt="Preview"
                             class="h-full w-full object-cover {{ $product->image ? '' : 'hidden' }}">
                        <span data-image-placeholder class="absolute inset-0 grid place-items-center text-gray-300 {{ $product->image ? 'hidden' : '' }}">
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
                    @if ($product->exists)
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
                    <input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $product->sort_order) }}" placeholder="Auto"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <p class="mt-1 text-xs text-gray-400">Lower numbers show first. Leave empty to auto-place.</p>
                </div>

                <div>
                    <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Availability</span>
                    <label class="flex cursor-pointer items-center justify-between rounded-lg border border-gray-200 px-3.5 py-3">
                        <span class="text-sm text-gray-600">In stock</span>
                        <input type="checkbox" name="in_stock" value="1" {{ old('in_stock', $product->in_stock ?? true) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-gray-300 text-brand focus:ring-brand">
                    </label>
                </div>

                <div>
                    <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Visibility</span>
                    <label class="flex cursor-pointer items-center justify-between rounded-lg border border-gray-200 px-3.5 py-3">
                        <span class="text-sm text-gray-600">Show in shop</span>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-gray-300 text-brand focus:ring-brand">
                    </label>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3">
                <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-brand text-sm font-semibold text-white transition hover:bg-brand-dark">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ $product->exists ? 'Save Changes' : 'Create Product' }}
                </button>
                <a href="{{ route('admin.products.index') }}" class="flex h-11 w-full items-center justify-center rounded-lg border border-gray-200 text-sm font-semibold text-navy transition hover:bg-gray-50">
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
