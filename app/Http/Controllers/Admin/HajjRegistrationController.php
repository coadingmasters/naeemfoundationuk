<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HajjRegistration;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class HajjRegistrationController extends Controller
{
    public function index()
    {
        $registrations = HajjRegistration::latest()->paginate(15);

        $stats = [
            'total' => HajjRegistration::count(),
            'passport' => HajjRegistration::where('has_pakistani_passport', true)->count(),
            'week' => HajjRegistration::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.hajj-registrations.index', compact('registrations', 'stats'));
    }

    public function destroy(HajjRegistration $registration): RedirectResponse
    {
        $registration->delete();

        return redirect()
            ->route('admin.hajj-registrations.index')
            ->with('success', 'Registration deleted.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'hajj-registrations-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['First name', 'Last name', 'Email', 'Phone', 'Pakistani passport', 'Submitted']);

            HajjRegistration::latest()->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->first_name,
                        $r->last_name,
                        $r->email,
                        $r->phone,
                        $r->has_pakistani_passport ? 'Yes' : 'No',
                        $r->created_at?->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
