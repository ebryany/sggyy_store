<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends BaseApiController
{
    /**
     * Get all notifications
     * 
     * GET /api/v1/notifications
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc');

        $notifications = $this->paginate($query, $request);

        return $this->successCollection(
            NotificationResource::collection($notifications)
        );
    }

    /**
     * Get unread notifications
     * 
     * GET /api/v1/notifications/unread
     */
    public function unread(Request $request)
    {
        $query = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc');

        $notifications = $this->paginate($query, $request);

        return $this->successCollection(
            NotificationResource::collection($notifications)
        );
    }

    /**
     * Mark notification as read
     * 
     * PATCH /api/v1/notifications/{notification_uuid}/read
     */
    public function markAsRead(Notification $notification)
    {
        // 🔒 SECURITY: Verify notification belongs to user
        if ($notification->user_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this notification');
        }

        $notification->update(['is_read' => true]);

        return $this->success(
            new NotificationResource($notification),
            'Notification marked as read'
        );
    }

    /**
     * Mark all notifications as read
     * 
     * PATCH /api/v1/notifications/read-all
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $this->success(null, 'All notifications marked as read');
    }
}

