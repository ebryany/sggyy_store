<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Notification;

class SendOrderNotification
{
    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        
        // Create notification for buyer
        Notification::create([
            'user_id' => $order->user_id,
            'message' => "Pesanan #{$order->order_number} berhasil dibuat! Silakan lakukan pembayaran.",
            'type' => 'order_created',
            'is_read' => false,
            'notifiable_type' => \App\Models\Order::class,
            'notifiable_id' => $order->id,
        ]);
        
        // Notify seller if order is for service (seller needs to work on it)
        if ($order->type === 'service' && $order->service) {
            $sellerId = $order->service->user_id;
            if ($sellerId) {
                Notification::create([
                    'user_id' => $sellerId,
                    'message' => "Pesanan baru #{$order->order_number} untuk jasa Anda: {$order->service->title}",
                    'type' => 'new_order',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\Order::class,
                    'notifiable_id' => $order->id,
                ]);
            }
        }
    }
}
