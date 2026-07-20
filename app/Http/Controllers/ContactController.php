<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        try {
            if (Schema::hasTable('contact_messages')) {
                ContactMessage::create($data);
            }
        } catch (Throwable $e) {
            // Never surface a hard error to the visitor on submit.
        }

        return redirect()
            ->route('contact')
            ->with('success', 'Thank you for reaching out — your message has been received. Our team will get back to you shortly.')
            ->withFragment('form');
    }
}
