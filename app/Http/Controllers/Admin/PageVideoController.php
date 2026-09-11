<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Concerns\RespondsToUploads;
use App\Http\Controllers\Controller;
use App\Models\PageVideo;
use App\Support\PageVideos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Lets an admin override the video shown under the hero on any Giving page.
 *
 * Rows are keyed by page slug (see App\Support\PageVideos::pages()), not by id,
 * so the edit screen works whether or not a custom row exists yet. A page with
 * no row simply shows the config default from config/appeal-videos.php.
 */
class PageVideoController extends Controller
{
    use HandlesImageUploads, RespondsToUploads;

    /** Directory (relative to the web root) where uploaded videos are stored. */
    private const UPLOAD_DIR = 'videos/pages';

    public function index()
    {
        $overrides = PageVideo::all()->keyBy('page_key');

        $rows = collect(PageVideos::pages())->map(fn ($label, $key) => [
            'key' => $key,
            'label' => $label,
            'video' => $overrides->get($key),
            'resolved' => PageVideos::resolve($key),
        ])->values();

        return view('admin.page-videos.index', compact('rows'));
    }

    public function create()
    {
        return view('admin.page-videos.create', [
            'video' => new PageVideo(['is_active' => true]),
            'pageKey' => old('page_key'),
            'pages' => PageVideos::pages(),
            'taken' => PageVideo::pluck('page_key')->all(),
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $pageKey = (string) $request->input('page_key');
        $request->merge(['page_key' => $pageKey]);

        $request->validate([
            'page_key' => ['required', Rule::in(array_keys(PageVideos::pages()))],
        ]);

        // The "add" form always carries a video — a link or an upload.
        $data = $this->validateData($request, exists: false);

        $this->deleteUploadedVideo(PageVideo::where('page_key', $pageKey)->value('video_url'));

        PageVideo::updateOrCreate(['page_key' => $pageKey], [
            'title' => $data['title'] ?: null,
            'is_active' => $request->boolean('is_active'),
            'video_url' => $this->resolveVideoUrl($request),
        ]);

        return $this->uploadRedirect($request, 'admin.page-videos.index', 'Video set for '.PageVideos::pages()[$pageKey].'.');
    }

    public function edit(string $pageKey)
    {
        abort_unless(PageVideos::isPage($pageKey), 404);

        $video = PageVideo::firstOrNew(
            ['page_key' => $pageKey],
            ['is_active' => true],
        );

        return view('admin.page-videos.edit', [
            'video' => $video,
            'pageKey' => $pageKey,
            'pageLabel' => PageVideos::pages()[$pageKey],
            'default' => config('appeal-videos.'.$pageKey, config('appeal-videos.default')),
        ]);
    }

    public function update(Request $request, string $pageKey): RedirectResponse|JsonResponse
    {
        abort_unless(PageVideos::isPage($pageKey), 404);

        $existing = PageVideo::where('page_key', $pageKey)->first();
        $data = $this->validateData($request, exists: (bool) $existing);

        $attributes = [
            'title' => $data['title'] ?: null,
            'is_active' => $request->boolean('is_active'),
        ];

        // Only replace the video when a new link or file is supplied.
        if ($request->hasFile('video_file')) {
            $this->deleteUploadedVideo($existing?->video_url);
            $attributes['video_url'] = $this->storeUploadedImage($request->file('video_file'), self::UPLOAD_DIR, 'page-video');
        } elseif ($request->filled('video_url')) {
            $this->deleteUploadedVideo($existing?->video_url);
            $attributes['video_url'] = trim((string) $request->input('video_url'));
        } elseif (! $existing) {
            throw ValidationException::withMessages([
                'video_url' => 'Provide a video link or upload a video file.',
            ]);
        }

        PageVideo::updateOrCreate(['page_key' => $pageKey], $attributes);

        return $this->uploadRedirect($request, 'admin.page-videos.index', 'Video updated for '.PageVideos::pages()[$pageKey].'.');
    }

    public function destroy(string $pageKey): RedirectResponse
    {
        abort_unless(PageVideos::isPage($pageKey), 404);

        $video = PageVideo::where('page_key', $pageKey)->first();

        if ($video) {
            $this->deleteUploadedVideo($video->video_url);
            $video->delete();
        }

        return redirect()->route('admin.page-videos.index')
            ->with('success', PageVideos::pages()[$pageKey].' is back to the default video.');
    }

    /**
     * Validate the shared fields. On create (no existing row) at least one
     * video source — a link or an upload — is required.
     *
     * @return array{title: string|null}
     */
    private function validateData(Request $request, bool $exists): array
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'video_url' => ['nullable', 'string', 'max:1000'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v,video/mpeg,video/x-msvideo', 'max:512000'],
        ]);

        if (! $exists && empty($validated['video_url']) && ! $request->hasFile('video_file')) {
            throw ValidationException::withMessages([
                'video_url' => 'Provide a video link or upload a video file.',
            ]);
        }

        return ['title' => $validated['title'] ?? null];
    }

    /** A new video's URL comes from an uploaded file if present, else the link. */
    private function resolveVideoUrl(Request $request): string
    {
        if ($request->hasFile('video_file')) {
            return $this->storeUploadedImage($request->file('video_file'), self::UPLOAD_DIR, 'page-video');
        }

        return trim((string) $request->input('video_url'));
    }

    /** Delete an uploaded video only if it lives in our upload directory. */
    private function deleteUploadedVideo(?string $path): void
    {
        $this->deleteUploadedImage($path, self::UPLOAD_DIR);
    }
}
