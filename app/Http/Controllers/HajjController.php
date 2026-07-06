<?php

namespace App\Http\Controllers;

use App\Models\HajjRegistration;
use App\Models\HajjVideo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class HajjController extends Controller
{
    public function index()
    {
        // Resilient fetch so the page never breaks before migrations run.
        $videos = collect();

        try {
            if (Schema::hasTable('hajj_videos')) {
                $videos = HajjVideo::active()->ordered()->get();
            }
        } catch (Throwable $e) {
            $videos = collect();
        }

        return view('hajj', compact('videos'));
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'has_pakistani_passport' => ['nullable', 'boolean'],
            'consent' => ['accepted'],
        ]);

        try {
            if (Schema::hasTable('hajj_registrations')) {
                HajjRegistration::create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'has_pakistani_passport' => $request->boolean('has_pakistani_passport'),
                ]);
            }
        } catch (Throwable $e) {
            // Never surface a hard error to the pilgrim on submit.
        }

        return redirect()
            ->route('hajj')
            ->with('success', 'Thank you! Your Hajj 2027 registration has been received — our team will be in touch soon.')
            ->withFragment('register');
    }
}
