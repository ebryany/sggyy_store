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
     * Create a notification
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

