<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VolunteerController extends Controller
{
    public function index()
    {
        $volunteers = Volunteer::latest()->paginate(15);

        $stats = [
            'total' => Volunteer::count(),
            'week' => Volunteer::where('created_at', '>=', now()->subDays(7))->count(),
            'notes' => Volunteer::whereNotNull('additional_info')->where('additional_info', '!=', '')->count(),
        ];

        return view('admin.volunteers.index', compact('volunteers', 'stats'));
    }

    public function destroy(Volunteer $volunteer): RedirectResponse
    {
        $volunteer->delete();

        return redirect()
            ->route('admin.volunteers.index')
            ->with('success', 'Volunteer sign-up deleted.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'volunteers-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Name', 'Email', 'Phone', 'Skillsets', 'Additional info', 'Submitted']);

            Volunteer::latest()->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $v) {
                    fputcsv($out, [
                        $v->name,
                        $v->email,
                        $v->phone,
                        implode(', ', $v->skillsets ?? []),
                        $v->additional_info,
                        $v->created_at?->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
