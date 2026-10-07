<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Tells the charity (not the customer) that a shop order has just been paid. */
class NewOrderAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $details  Customer details from checkout.
     * @param  array<int, array<string, mixed>>  $items
     */
    public function __construct(
        public string $reference,
        public array $details,
        public array $items,
        public float $subtotal,
        public string $currency,
        public string $symbol,
        public string $paymentId,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New shop order: '.$this->symbol.number_format($this->subtotal, 2).' from '.($this->details['name'] ?? 'a customer'),
            replyTo: ! empty($this->details['email']) ? [new Address($this->details['email'], $this->details['name'] ?? null)] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-order-alert',
        );
    }
}
