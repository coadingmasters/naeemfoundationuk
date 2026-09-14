<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class CareerController extends Controller
{
    use HandlesImageUploads;

    /** Directory (relative to the web root) where uploaded CVs are stored. */
    private const UPLOAD_DIR = 'files/cvs';

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:2000'],
            // Broad format support so a CV in any common format is accepted —
            // no one should be blocked from applying by a file-type error.
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx,odt,rtf,txt', 'max:5120'],
        ]);

        try {
            if (Schema::hasTable('job_applications')) {
                JobApplication::create([
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                    'postcode' => $data['postcode'] ?? null,
                    'address' => $data['address'] ?? null,
                    'cv_path' => $request->hasFile('cv')
                        ? $this->storeUploadedImage($request->file('cv'), self::UPLOAD_DIR, 'cv')
                        : null,
                ]);
            }
        } catch (Throwable $e) {
            // Never surface a hard error to the visitor on submit.
        }

        return redirect()
            ->route('careers')
            ->with('success', 'Thank you! We\'ve received your application and will be in touch soon.')
            ->withFragment('apply');
    }
}
