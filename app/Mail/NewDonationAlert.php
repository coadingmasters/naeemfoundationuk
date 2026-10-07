<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Tells the charity (not the donor) that a donation has just come in. */
class NewDonationAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $details  Donor details from checkout.
     * @param  array<int, array<string, mixed>>  $items
     */
    public function __construct(
        public string $reference,
        public array $details,
        public array $items,
        public float $subtotal,
        public float $fee,
        public float $total,
        public string $currency,
        public string $currencySymbol,
        public string $paymentId,
        public bool $recurring = false,
    ) {}

    public function envelope(): Envelope
    {
        $name = trim(($this->details['first_name'] ?? '').' '.($this->details['last_name'] ?? ''));

        return new Envelope(
            subject: 'New donation: '.$this->currencySymbol.number_format($this->total, 2)
                .($this->recurring ? ' (recurring)' : '').' from '.($name ?: 'a donor'),
            // Replying goes straight to the donor.
            replyTo: ! empty($this->details['email']) ? [new Address($this->details['email'], $name ?: null)] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-donation-alert',
        );
    }
}
