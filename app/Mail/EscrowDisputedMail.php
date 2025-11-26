<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EscrowDisputedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $disputedBy
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Dispute Dibuat untuk Pesanan #{$this->order->order_number} - Ebrystoree",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.escrow.disputed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

