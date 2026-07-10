<?php

namespace App\Http\Controllers;

use App\Models\CommunityEnquiry;
use App\Models\CommunityVideo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class CommunityCentreController extends Controller
{
    public function index()
    {
        // Resilient fetch so the page never breaks before migrations run.
        $videos = collect();

        try {
            if (Schema::hasTable('community_videos')) {
                $videos = CommunityVideo::active()->ordered()->get();
            }
        } catch (Throwable $e) {
            $videos = collect();
        }

        return view('community-centre', compact('videos'));
    }

    public function enquire(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        try {
            if (Schema::hasTable('community_enquiries')) {
                CommunityEnquiry::create($data);
            }
        } catch (Throwable $e) {
            // Never surface a hard error to the visitor on submit.
        }

        return redirect()
            ->route('community-centre')
            ->with('success', 'JazakAllah khair — your enquiry has been received. Our team will be in touch shortly.')
            ->withFragment('enquire');
    }
}
