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
}
