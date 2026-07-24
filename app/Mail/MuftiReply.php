<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MuftiReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $question,
        public string $answer,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Ask a Mufti question — a response from '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.mufti-reply',
        );
    }
}
