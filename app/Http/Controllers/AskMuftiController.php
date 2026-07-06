<?php

namespace App\Http\Controllers;

use App\Models\MuftiQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AskMuftiController extends Controller
{
    public function index()
    {
        return view('ask-mufti');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        try {
            if (Schema::hasTable('mufti_questions')) {
                MuftiQuestion::create($data);
            }
        } catch (Throwable $e) {
            // Never surface a hard error to the visitor on submit.
        }

        return redirect()
            ->route('ask-mufti')
            ->with('success', 'JazakAllah khair — your question has been received. A member of our scholars’ team will respond soon.')
            ->withFragment('form');
    }
}
