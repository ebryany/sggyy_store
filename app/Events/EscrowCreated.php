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

class EscrowCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Escrow $escrow;
    public Order $order;

    public function __construct(Escrow $escrow, Order $order)
    {
        $this->escrow = $escrow;
        $this->order = $order;
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
        return 'escrow.created';
    }

    public function broadcastWith(): array
    {
        return [
            'escrow_id' => $this->escrow->id,
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'amount' => $this->escrow->amount,
            'status' => $this->escrow->status,
            'hold_until' => $this->escrow->hold_until?->toIso8601String(),
            'created_at' => $this->escrow->created_at->toIso8601String(),
        ];
    }
}

