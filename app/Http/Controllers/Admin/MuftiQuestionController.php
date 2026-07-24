<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MuftiReply;
use App\Models\MuftiQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

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

    /** Email a scholar's answer straight to the person who asked. */
    public function reply(Request $request, MuftiQuestion $muftiQuestion): RedirectResponse
    {
        $data = $request->validate([
            'answer' => ['required', 'string', 'max:8000'],
        ]);

        try {
            Mail::to($muftiQuestion->email)->send(new MuftiReply(
                name: $muftiQuestion->name,
                question: $muftiQuestion->message,
                answer: $data['answer'],
            ));
        } catch (Throwable $e) {
            return back()->with('error', 'Could not send the email right now. Please try again.');
        }

        $muftiQuestion->update([
            'answer' => $data['answer'],
            'answered_at' => now(),
        ]);

        return redirect()
            ->route('admin.mufti-questions.index')
            ->with('success', 'Reply sent to '.$muftiQuestion->name.' at '.$muftiQuestion->email.'.');
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
