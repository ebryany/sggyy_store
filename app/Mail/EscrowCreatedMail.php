<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EscrowCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public int $holdPeriodDays
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Dana Ditahan di Escrow untuk Pesanan #{$this->order->order_number} - Ebrystoree",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.escrow.created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
