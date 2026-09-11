<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AssignsSortOrder;
use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Concerns\RespondsToUploads;
use App\Http\Controllers\Controller;
use App\Models\HajjStepVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Full CRUD for the "Steps of Hajj" video gallery on the Hajj page — an open
 * list, add/edit/delete as many videos as needed (mirrors HajjVideoController).
 */
class HajjStepVideoController extends Controller
{
    use AssignsSortOrder, HandlesImageUploads, RespondsToUploads;

    /** Directory (relative to the web root) where uploaded step videos are stored. */
    private const UPLOAD_DIR = 'videos/hajj-steps';

    public function index()
    {
        $videos = HajjStepVideo::ordered()->paginate(12);

        return view('admin.hajj-step-videos.index', compact('videos'));
    }

    public function create()
    {
        $video = new HajjStepVideo(['is_active' => true]);

        return view('admin.hajj-step-videos.create', compact('video'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $data = $this->validateData($request);
        $data['sort_order'] = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : $this->nextSortOrder(HajjStepVideo::class);
        $data['is_active'] = $request->boolean('is_active');
        $data['video_url'] = $this->resolveVideoUrl($request);

        HajjStepVideo::create($data);

        return $this->uploadRedirect($request, 'admin.hajj-step-videos.index', 'Video added successfully.');
    }

    public function edit(HajjStepVideo $hajjStepVideo)
    {
        return view('admin.hajj-step-videos.edit', ['video' => $hajjStepVideo]);
    }

    public function update(Request $request, HajjStepVideo $hajjStepVideo): RedirectResponse|JsonResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        if (! $request->filled('sort_order')) {
            unset($data['sort_order']); // keep the existing order
        }

        // Only replace the URL/file when a new one is supplied.
        if ($request->hasFile('video_file')) {
            $this->deleteUploadedVideo($hajjStepVideo->video_url);
            $data['video_url'] = $this->storeUploadedImage($request->file('video_file'), self::UPLOAD_DIR, 'hajj-step');
        } elseif ($request->filled('video_url')) {
            $this->deleteUploadedVideo($hajjStepVideo->video_url);
            $data['video_url'] = trim($request->input('video_url'));
        } else {
            unset($data['video_url']);
        }

        $hajjStepVideo->update($data);

        return $this->uploadRedirect($request, 'admin.hajj-step-videos.index', 'Video updated successfully.');
    }

    public function destroy(HajjStepVideo $hajjStepVideo): RedirectResponse
    {
        $this->deleteUploadedVideo($hajjStepVideo->video_url);
        $hajjStepVideo->delete();

        return redirect()->route('admin.hajj-step-videos.index')
            ->with('success', 'Video deleted successfully.');
    }

    /** Validate the shared fields (title, order, and the two video sources). */
    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'video_url' => ['nullable', 'string', 'max:1000'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v,video/mpeg,video/x-msvideo', 'max:512000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        // On create, at least one source is required.
        if (! $request->routeIs('*.update')
            && empty($validated['video_url'])
            && ! $request->hasFile('video_file')) {
            throw ValidationException::withMessages([
                'video_url' => 'Provide a video link or upload a video file.',
            ]);
        }

        return array_intersect_key($validated, array_flip(['title', 'sort_order']));
    }

    /** A new video's URL comes from an uploaded file if present, else the link. */
    private function resolveVideoUrl(Request $request): string
    {
        if ($request->hasFile('video_file')) {
            return $this->storeUploadedImage($request->file('video_file'), self::UPLOAD_DIR, 'hajj-step');
        }

        return trim((string) $request->input('video_url'));
    }

    /** Delete an uploaded video only if it lives in our upload directory. */
    private function deleteUploadedVideo(?string $path): void
    {
        $this->deleteUploadedImage($path, self::UPLOAD_DIR);
    }
}
