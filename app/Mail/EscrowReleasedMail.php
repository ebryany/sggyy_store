<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EscrowReleasedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $releaseType
    ) {}

    public function envelope(): Envelope
    {
        $releaseTypeLabels = [
            'early' => 'Dilepas Lebih Awal',
            'auto' => 'Dilepas Otomatis',
            'manual' => 'Dilepas Manual',
        ];
        
        return new Envelope(
            subject: "Escrow untuk Pesanan #{$this->order->order_number} Telah Dilepas - {$releaseTypeLabels[$this->releaseType] ?? 'Dilepas'}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.escrow.released',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

