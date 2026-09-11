<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\HajjStepVideo;
use App\Support\HajjSteps;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Lets an admin set a video for any of the 8 "Steps of Hajj" cards, replacing
 * that step's plain description with a video on the public Hajj page. There
 * are always exactly 8 fixed steps (App\Support\HajjSteps) — no create/delete
 * of steps themselves, only editing or clearing each one's video.
 */
class HajjStepVideoController extends Controller
{
    use HandlesImageUploads;

    /** Directory (relative to the web root) where uploaded step videos are stored. */
    private const UPLOAD_DIR = 'videos/hajj-steps';

    public function index()
    {
        $overrides = HajjStepVideo::all()->keyBy('step_key');

        $rows = collect(HajjSteps::all())->map(fn ($step, $key) => [
            'key' => $key,
            'label' => $step['title'],
            'video' => $overrides->get($key),
        ])->values();

        return view('admin.hajj-step-videos.index', compact('rows'));
    }

    public function edit(string $stepKey)
    {
        abort_unless(HajjSteps::isStep($stepKey), 404);

        $video = HajjStepVideo::firstOrNew(
            ['step_key' => $stepKey],
            ['is_active' => true],
        );

        return view('admin.hajj-step-videos.edit', [
            'video' => $video,
            'stepKey' => $stepKey,
            'stepLabel' => HajjSteps::all()[$stepKey]['title'],
        ]);
    }

    public function update(Request $request, string $stepKey): RedirectResponse
    {
        abort_unless(HajjSteps::isStep($stepKey), 404);

        $existing = HajjStepVideo::where('step_key', $stepKey)->first();

        $validated = $request->validate([
            'video_url' => ['nullable', 'string', 'max:1000'],
            'video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg', 'max:512000'],
        ]);

        $attributes = ['is_active' => $request->boolean('is_active', true)];

        if ($request->hasFile('video_file')) {
            if ($existing) {
                $this->deleteUploadedVideo($existing->video_url);
            }
            $attributes['video_url'] = $this->storeUploadedImage($request->file('video_file'), self::UPLOAD_DIR, 'hajj-step');
        } elseif (filled($validated['video_url'] ?? null)) {
            if ($existing) {
                $this->deleteUploadedVideo($existing->video_url);
            }
            $attributes['video_url'] = trim($validated['video_url']);
        } elseif (! $existing) {
            throw ValidationException::withMessages([
                'video_url' => 'Provide a video link or upload a video file.',
            ]);
        }

        HajjStepVideo::updateOrCreate(['step_key' => $stepKey], $attributes);

        return redirect()->route('admin.hajj-step-videos.index')
            ->with('success', 'Video set for '.HajjSteps::all()[$stepKey]['title'].'.');
    }

    public function destroy(string $stepKey): RedirectResponse
    {
        abort_unless(HajjSteps::isStep($stepKey), 404);

        $video = HajjStepVideo::where('step_key', $stepKey)->first();

        if ($video) {
            $this->deleteUploadedVideo($video->video_url);
            $video->delete();
        }

        return redirect()->route('admin.hajj-step-videos.index')
            ->with('success', HajjSteps::all()[$stepKey]['title'].' is back to the default text.');
    }

    /** Delete an uploaded video only if it lives in our upload directory. */
    private function deleteUploadedVideo(?string $path): void
    {
        $this->deleteUploadedImage($path, self::UPLOAD_DIR);
    }
}
