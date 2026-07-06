<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AssignsSortOrder;
use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\HajjVideo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HajjVideoController extends Controller
{
    use AssignsSortOrder, HandlesImageUploads;

    /** Directory (relative to the web root) where uploaded videos are stored. */
    private const UPLOAD_DIR = 'videos/hajj';

    public function index()
    {
        $videos = HajjVideo::ordered()->paginate(10);

        return view('admin.hajj-videos.index', compact('videos'));
    }

    public function create()
    {
        $video = new HajjVideo(['is_active' => true]);

        return view('admin.hajj-videos.create', compact('video'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['sort_order'] = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : $this->nextSortOrder(HajjVideo::class);
        $data['is_active'] = $request->boolean('is_active');
        $data['video_url'] = $this->resolveVideoUrl($request);

        HajjVideo::create($data);

        return redirect()->route('admin.hajj-videos.index')
            ->with('success', 'Video added successfully.');
    }

    public function edit(HajjVideo $hajjVideo)
    {
        return view('admin.hajj-videos.edit', ['video' => $hajjVideo]);
    }

    public function update(Request $request, HajjVideo $hajjVideo): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        if (! $request->filled('sort_order')) {
            unset($data['sort_order']); // keep the existing order
        }

        // Only replace the URL/file when a new one is supplied.
        if ($request->hasFile('video_file')) {
            $this->deleteUploadedVideo($hajjVideo->video_url);
            $data['video_url'] = $this->storeUploadedImage($request->file('video_file'), self::UPLOAD_DIR, 'hajj-video');
        } elseif ($request->filled('video_url')) {
            $this->deleteUploadedVideo($hajjVideo->video_url);
            $data['video_url'] = trim($request->input('video_url'));
        } else {
            unset($data['video_url']);
        }

        $hajjVideo->update($data);

        return redirect()->route('admin.hajj-videos.index')
            ->with('success', 'Video updated successfully.');
    }

    public function destroy(HajjVideo $hajjVideo): RedirectResponse
    {
        $this->deleteUploadedVideo($hajjVideo->video_url);
        $hajjVideo->delete();

        return redirect()->route('admin.hajj-videos.index')
            ->with('success', 'Video deleted successfully.');
    }

    /** Validate the shared fields (title, order, and the two video sources). */
    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'video_url' => ['nullable', 'string', 'max:1000'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg', 'max:51200'],
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
            return $this->storeUploadedImage($request->file('video_file'), self::UPLOAD_DIR, 'hajj-video');
        }

        return trim((string) $request->input('video_url'));
    }

    /** Delete an uploaded video only if it lives in our upload directory. */
    private function deleteUploadedVideo(?string $path): void
    {
        $this->deleteUploadedImage($path, self::UPLOAD_DIR);
    }
}
