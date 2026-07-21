<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class VolunteerController extends Controller
{
    public function index()
    {
        return view('volunteer');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:255'],
            'skillsets' => ['nullable', 'array'],
            'skillsets.*' => ['string', 'max:60'],
            'additional_info' => ['nullable', 'string', 'max:5000'],
            'agree' => ['accepted'],
        ], [
            'agree.accepted' => 'Please agree to the privacy policy to continue.',
        ]);

        try {
            if (Schema::hasTable('volunteers')) {
                Volunteer::create([
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'skillsets' => $data['skillsets'] ?? [],
                    'additional_info' => $data['additional_info'] ?? null,
                ]);
            }
        } catch (Throwable $e) {
            // Never surface a hard error to the visitor on submit.
        }

        return redirect()
            ->route('volunteer')
            ->with('success', 'JazakAllah khair — your volunteer request has been received. Our team will be in touch with you soon.')
            ->withFragment('form');
    }
}
