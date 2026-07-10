{{-- Shared fields for create/edit. Expects $video (CommunityVideo, possibly unsaved). --}}
<div class="grid gap-6 lg:grid-cols-3">
    {{-- Main fields --}}
    <div class="space-y-5 lg:col-span-2">
        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-navy-dark">Video details</h3>

            <div class="space-y-5">
                <div>
                    <label for="title" class="mb-1.5 block text-sm font-semibold text-navy-dark">Title <span class="text-red-500">*</span></label>
                    <input id="title" name="title" type="text" value="{{ old('title', $video->title) }}" required
                           placeholder="Youth Programmes in Action"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                </div>

                <div>
                    <label for="video_url" class="mb-1.5 block text-sm font-semibold text-navy-dark">Video link</label>
                    <input id="video_url" name="video_url" type="text" value="{{ old('video_url', \Illuminate\Support\Str::startsWith($video->video_url ?? '', ['http://', 'https://']) ? $video->video_url : '') }}"
                           placeholder="https://www.youtube.com/watch?v=…  or  https://…/video.mp4"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <p class="mt-1 text-xs text-gray-400">Paste a YouTube / Vimeo link or a direct video file URL. {{ $video->exists ? 'Leave empty to keep the current video.' : '' }}</p>
                </div>

                <div class="flex items-center gap-3 text-xs font-semibold uppercase tracking-wide text-gray-300">
                    <span class="h-px flex-1 bg-gray-100"></span>or upload a file<span class="h-px flex-1 bg-gray-100"></span>
                </div>

                <div>
                    <label for="video_file" class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-4 py-6 text-center transition hover:border-brand hover:bg-cream/40">
                        <svg class="h-6 w-6 text-brand" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V4m0 0L8 8m4-4l4 4M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span class="mt-2 text-sm font-semibold text-navy-dark" data-file-label>Click to upload a video</span>
                        <span class="mt-0.5 text-xs text-gray-400">MP4, WEBM or OGG — up to 50 MB</span>
                        <input id="video_file" name="video_file" type="file" accept="video/mp4,video/webm,video/ogg" data-file-input class="sr-only">
                    </label>
                    @if ($video->exists && ! \Illuminate\Support\Str::startsWith($video->video_url, ['http://', 'https://']))
                        <p class="mt-2 text-xs text-gray-400">Currently using an uploaded file. Upload a new one to replace it.</p>
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
                    <input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $video->sort_order) }}" placeholder="Auto"
                           class="h-11 w-full rounded-lg border border-gray-300 bg-white px-3.5 text-sm text-navy-dark outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/30">
                    <p class="mt-1 text-xs text-gray-400">Leave empty to place it automatically. Lower numbers show first.</p>
                </div>

                <div>
                    <span class="mb-1.5 block text-sm font-semibold text-navy-dark">Visibility</span>
                    <label class="flex cursor-pointer items-center justify-between rounded-lg border border-gray-200 px-3.5 py-3">
                        <span class="text-sm text-gray-600">Show on website</span>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $video->is_active ?? true) ? 'checked' : '' }}
                               class="h-5 w-5 rounded border-gray-300 text-brand focus:ring-brand">
                    </label>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3">
                <button type="submit" class="flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-brand text-sm font-semibold text-white transition hover:bg-brand-dark">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ $video->exists ? 'Save Changes' : 'Add Video' }}
                </button>
                <a href="{{ route('admin.community-videos.index') }}" class="flex h-11 w-full items-center justify-center rounded-lg border border-gray-200 text-sm font-semibold text-navy transition hover:bg-gray-50">
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
        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            label.textContent = file ? file.name : 'Click to upload a video';
        });
    })();
</script>
@endpush
