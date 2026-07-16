<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceipt extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    public function __construct(
        public string $reference,
        public string $name,
        public array $items,
        public float $subtotal,
        public float $fee,
        public float $total,
        public bool $giftAid,
        public string $currencySymbol = '£',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank you for your donation — '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-receipt',
        );
    }
}
