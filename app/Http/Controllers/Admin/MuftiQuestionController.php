<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MuftiQuestion;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MuftiQuestionController extends Controller
{
    public function index()
    {
        $questions = MuftiQuestion::latest()->paginate(15);

        $stats = [
            'total' => MuftiQuestion::count(),
            'week' => MuftiQuestion::where('created_at', '>=', now()->subDays(7))->count(),
            'today' => MuftiQuestion::where('created_at', '>=', now()->startOfDay())->count(),
        ];

        return view('admin.mufti-questions.index', compact('questions', 'stats'));
    }

    public function destroy(MuftiQuestion $muftiQuestion): RedirectResponse
    {
        $muftiQuestion->delete();

        return redirect()
            ->route('admin.mufti-questions.index')
            ->with('success', 'Question deleted.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'ask-a-mufti-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Name', 'Email', 'Phone', 'Question', 'Submitted']);

            MuftiQuestion::latest()->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $q) {
                    fputcsv($out, [
                        $q->name,
                        $q->email,
                        $q->phone,
                        $q->message,
                        $q->created_at?->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
