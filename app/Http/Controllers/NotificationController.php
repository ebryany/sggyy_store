<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $user = auth()->user();
        $notifications = $this->notificationService->getNotifications($user, 20);
        $unreadCount = $this->notificationService->getUnreadCount($user);
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(Notification $notification): RedirectResponse
    {
        // Authorization: only owner can mark as read
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $this->notificationService->markAsRead($notification);
        
        return back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca');
    }

    public function markAllAsRead(): RedirectResponse
    {
        $this->notificationService->markAllAsRead(auth()->user());
        
        return back()->with('success', 'Semua notifikasi ditandai sebagai sudah dibaca');
    }

    /**
     * API endpoint to get unread notifications (for real-time polling)
     */
    public function getUnreadNotifications(Request $request)
    {
        $user = auth()->user();
        $notifications = $this->notificationService->getUnreadNotifications($user, 5);
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'message' => $notification->message,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->toISOString(),
                    'action_url' => $this->getActionUrl($notification),
                    'action_text' => $this->getActionText($notification),
                ];
            }),
            'unread_count' => $this->notificationService->getUnreadCount($user),
        ]);
    }

    /**
     * Get action URL for notification
     */
    private function getActionUrl(Notification $notification): ?string
    {
        if (!$notification->notifiable_type || !$notification->notifiable_id) {
            return null;
        }

        try {
            return match($notification->notifiable_type) {
                'App\Models\Order' => route('orders.show', $notification->notifiable_id),
                'App\Models\Chat' => $notification->notifiable 
                    ? route('chat.show', $notification->notifiable->getOtherUser()?->id ?? $notification->notifiable_id)
                    : null,
                'App\Models\SellerVerification' => route('seller.verification.index'),
                default => null,
            };
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get action text for notification
     */
    private function getActionText(Notification $notification): ?string
    {
        if (!$notification->notifiable_type) {
            return null;
        }

        return match($notification->notifiable_type) {
            'App\Models\Order' => 'Lihat Pesanan',
            'App\Models\Chat' => 'Buka Chat',
            'App\Models\SellerVerification' => 'Lihat Status',
            default => 'Lihat Detail',
        };
    }
}
