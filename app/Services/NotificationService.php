<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Get notifications for a user
     * 
     * @param User $user
     * @param int $limit
     * @return Collection<Notification>
     */
    public function getNotifications(User $user, int $limit = 20): Collection
    {
        return Notification::where('user_id', $user->id)
            ->with('notifiable')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get unread notifications count for a user
     * 
     * @param User $user
     * @return int
     */
    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get unread notifications for a user (for real-time display)
     * 
     * @param User $user
     * @param int $limit
     * @return Collection<Notification>
     */
    public function getUnreadNotifications(User $user, int $limit = 5): Collection
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->with('notifiable')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark a notification as read
     * 
     * @param Notification $notification
     * @return Notification
     * @throws \Exception
     */
    public function markAsRead(Notification $notification): Notification
    {
        try {
            if ($notification->is_read) {
                return $notification; // Already read
            }

            $notification->update(['is_read' => true]);

            Log::info('Notification marked as read', [
                'notification_id' => $notification->id,
                'user_id' => $notification->user_id,
            ]);

            return $notification->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal menandai notifikasi sebagai sudah dibaca: ' . $e->getMessage());
        }
    }

    /**
     * Mark all notifications as read for a user
     * 
     * @param User $user
     * @return int Number of notifications marked as read
     * @throws \Exception
     */
    public function markAllAsRead(User $user): int
    {
        try {
            $count = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            Log::info('All notifications marked as read', [
                'user_id' => $user->id,
                'count' => $count,
            ]);

            return $count;
        } catch (\Exception $e) {
            Log::error('Failed to mark all notifications as read', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal menandai semua notifikasi sebagai sudah dibaca: ' . $e->getMessage());
        }
    }

    /**
     * Create a notification with idempotency check
     * 
     * @param User $user
     * @param string $type
     * @param string $message
     * @param Model|null $notifiable
     * @param int $withinMinutes Time window for duplicate check (default: 5 minutes)
     * @return Notification|null Returns null if duplicate notification exists
     * @throws \Exception
     */
    public function createNotificationIfNotExists(
        User $user,
        string $type,
        string $message,
        ?Model $notifiable = null,
        int $withinMinutes = 5
    ): ?Notification {
        // 🔒 SECURITY: Validate user exists
        if (!User::find($user->id)) {
            Log::warning('Notification skipped - user not found', [
                'user_id' => $user->id,
                'type' => $type,
            ]);
            return null;
        }

        // 🔒 SECURITY: Validate order status if notifiable is Order
        if ($notifiable instanceof \App\Models\Order) {
            if ($notifiable->status === 'cancelled') {
                Log::info('Notification skipped - order cancelled', [
                    'order_id' => $notifiable->id,
                    'type' => $type,
                ]);
                return null;
            }
        }

        // 🔒 IDEMPOTENCY: Check for duplicate notification within time window
        $exists = Notification::where('user_id', $user->id)
            ->where('type', $type)
            ->where('notifiable_type', $notifiable ? get_class($notifiable) : null)
            ->where('notifiable_id', $notifiable?->id)
            ->where('created_at', '>=', now()->subMinutes($withinMinutes))
            ->exists();

        if ($exists) {
            Log::info('Duplicate notification prevented (idempotency check)', [
                'user_id' => $user->id,
                'type' => $type,
                'notifiable_type' => $notifiable ? get_class($notifiable) : null,
                'notifiable_id' => $notifiable?->id,
                'within_minutes' => $withinMinutes,
            ]);
            return null;
        }

        return $this->createNotification($user, $type, $message, $notifiable);
    }

    /**
     * Create a notification (without idempotency check - use createNotificationIfNotExists for safety)
     * 
     * @param User $user
     * @param string $type
     * @param string $message
     * @param Model|null $notifiable
     * @return Notification
     * @throws \Exception
     */
    public function createNotification(
        User $user,
        string $type,
        string $message,
        ?Model $notifiable = null
    ): Notification {
        try {
            $notification = Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'message' => $message,
                'is_read' => false,
                'notifiable_type' => $notifiable ? get_class($notifiable) : null,
                'notifiable_id' => $notifiable?->id,
            ]);

            Log::info('Notification created', [
                'notification_id' => $notification->id,
                'user_id' => $user->id,
                'type' => $type,
            ]);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create notification', [
                'user_id' => $user->id,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal membuat notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Delete old read notifications (cleanup)
     * 
     * @param int $daysOld Number of days old to delete
     * @return int Number of notifications deleted
     */
    public function deleteOldReadNotifications(int $daysOld = 30): int
    {
        try {
            $count = Notification::where('is_read', true)
                ->where('created_at', '<', now()->subDays($daysOld))
                ->delete();

            Log::info('Old read notifications deleted', [
                'count' => $count,
                'days_old' => $daysOld,
            ]);

            return $count;
        } catch (\Exception $e) {
            Log::error('Failed to delete old read notifications', [
                'error' => $e->getMessage(),
            ]);

            return 0;
        }
    }
}

