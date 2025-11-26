<?php

namespace App\Events;

use App\Models\Escrow;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EscrowReleased implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Escrow $escrow;
    public Order $order;
    public string $releaseType;

    public function __construct(Escrow $escrow, Order $order, string $releaseType)
    {
        $this->escrow = $escrow;
        $this->order = $order;
        $this->releaseType = $releaseType;
    }

    public function broadcastOn(): array
    {
        $buyerId = $this->order->user_id;
        $sellerId = $this->order->product ? $this->order->product->user_id : $this->order->service->user_id;

        return [
            new PrivateChannel('user.' . $buyerId),
            new PrivateChannel('user.' . $sellerId),
            new PrivateChannel('order.' . $this->order->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'escrow.released';
    }

    public function broadcastWith(): array
    {
        return [
            'escrow_id' => $this->escrow->id,
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'amount' => $this->escrow->amount,
            'seller_earning' => $this->escrow->seller_earning,
            'status' => $this->escrow->status,
            'release_type' => $this->releaseType,
            'released_at' => $this->escrow->released_at?->toIso8601String(),
            'released_by' => $this->escrow->released_by,
        ];
    }
}

