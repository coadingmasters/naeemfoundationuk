<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesImageUploads;
use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;

class JobApplicationController extends Controller
{
    use HandlesImageUploads;

    private const UPLOAD_DIR = 'files/cvs';

    public function index()
    {
        $applications = JobApplication::latest()->paginate(15);

        $stats = [
            'total' => JobApplication::count(),
            'week' => JobApplication::where('created_at', '>=', now()->subDays(7))->count(),
            'with_cv' => JobApplication::whereNotNull('cv_path')->count(),
        ];

        return view('admin.job-applications.index', compact('applications', 'stats'));
    }

    public function destroy(JobApplication $jobApplication): RedirectResponse
    {
        $this->deleteUploadedImage($jobApplication->cv_path, self::UPLOAD_DIR);
        $jobApplication->delete();

        return redirect()
            ->route('admin.job-applications.index')
            ->with('success', 'Application deleted.');
    }
}
