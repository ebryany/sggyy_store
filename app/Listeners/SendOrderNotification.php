<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Notification;

class SendOrderNotification
{
    /**
     * Handle the event.
     * 🔒 FIX: Use NotificationService with idempotency check
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        $notificationService = app(\App\Services\NotificationService::class);
        
        // Create notification for buyer
        $notificationService->createNotificationIfNotExists(
            $order->user,
            'order_created',
            "Pesanan #{$order->order_number} berhasil dibuat! Silakan lakukan pembayaran.",
            $order,
            10 // 10 minutes window for duplicate check
        );
        
        // Notify seller if order is for service (seller needs to work on it)
        if ($order->type === 'service' && $order->service) {
            $sellerId = $order->service->user_id;
            if ($sellerId) {
                $seller = \App\Models\User::find($sellerId);
                if ($seller) {
                    $notificationService->createNotificationIfNotExists(
                        $seller,
                        'new_order',
                        "Pesanan baru #{$order->order_number} untuk jasa Anda: {$order->service->title}",
                        $order,
                        10 // 10 minutes window for duplicate check
                    );
                }
            }
        }
    }
}
