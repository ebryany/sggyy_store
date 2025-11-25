@auth
<div x-data="notificationToast()" 
     class="fixed left-0 right-0 z-50 px-4 pointer-events-none"
     style="top: calc(var(--navbar-height, 80px) + 0.5rem); margin-top: 0.5rem;">
    <div class="container mx-auto max-w-2xl">
        <!-- Notification Stack -->
        <template x-for="(notification, index) in notifications" :key="notification.id">
            <div 
                x-show="notification.visible"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-x-full"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-x-0"
                x-transition:leave-end="opacity-0 transform translate-x-full"
                class="mb-3 pointer-events-auto"
                :style="`z-index: ${1000 - index}`">
                <div class="glass border rounded-xl p-4 shadow-2xl backdrop-blur-xl"
                     :class="{
                         'bg-primary/20 border-primary/50': !notification.is_read,
                         'bg-white/5 border-white/10': notification.is_read,
                         'border-l-4': !notification.is_read
                     }">
                    <div class="flex items-start gap-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center"
                             :class="{
                                 'bg-primary/20 border border-primary/30': !notification.is_read,
                                 'bg-white/10 border border-white/10': notification.is_read
                             }">
                            <template x-if="notification.type === 'order_created' || notification.type === 'new_order'">
                                <x-icon name="shopping-bag" 
                                        x-bind:class="notification.is_read ? 'w-5 h-5 text-white/60' : 'w-5 h-5 text-primary'" />
                            </template>
                            <template x-if="notification.type === 'payment_verified'">
                                <x-icon name="check" 
                                        x-bind:class="notification.is_read ? 'w-5 h-5 text-white/60' : 'w-5 h-5 text-primary'" />
                            </template>
                            <template x-if="notification.type === 'order_message' || notification.type === 'chat_message'">
                                <x-icon name="message-square" 
                                        x-bind:class="notification.is_read ? 'w-5 h-5 text-white/60' : 'w-5 h-5 text-primary'" />
                            </template>
                            <template x-if="notification.type === 'progress_milestone'">
                                <x-icon name="chart" 
                                        x-bind:class="notification.is_read ? 'w-5 h-5 text-white/60' : 'w-5 h-5 text-primary'" />
                            </template>
                            <template x-if="notification.type === 'deadline_passed'">
                                <x-icon name="clock" 
                                        x-bind:class="notification.is_read ? 'w-5 h-5 text-white/60' : 'w-5 h-5 text-primary'" />
                            </template>
                            <template x-if="notification.type === 'seller_verified'">
                                <x-icon name="shield" 
                                        x-bind:class="notification.is_read ? 'w-5 h-5 text-white/60' : 'w-5 h-5 text-primary'" />
                            </template>
                            <template x-if="!['order_created', 'new_order', 'payment_verified', 'order_message', 'chat_message', 'progress_milestone', 'deadline_passed', 'seller_verified'].includes(notification.type)">
                                <x-icon name="bell" 
                                        x-bind:class="notification.is_read ? 'w-5 h-5 text-white/60' : 'w-5 h-5 text-primary'" />
                            </template>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm sm:text-base break-words mb-1"
                               :class="notification.is_read ? 'text-white/90' : 'text-white'"
                               x-text="notification.message"></p>
                            <p class="text-xs text-white/60" x-text="notification.time_ago"></p>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2 mt-2" x-show="notification.action_url && notification.action_url !== null">
                                <a :href="notification.action_url" 
                                   class="inline-flex items-center gap-1 text-xs px-3 py-1.5 bg-primary/20 hover:bg-primary/30 text-primary rounded-lg transition-colors border border-primary/30"
                                   @click="markAsRead(notification.id)">
                                    <x-icon name="arrow-right" class="w-3 h-3" />
                                    <span x-text="notification.action_text || 'Lihat Detail'"></span>
                                </a>
                            </div>
                        </div>
                        
                        <!-- Close Button -->
                        <button @click="dismiss(notification.id)" 
                                class="flex-shrink-0 text-white/60 hover:text-white transition-colors touch-target">
                            <x-icon name="x" class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function notificationToast() {
    return {
        notifications: [],
        pollingInterval: null,
        lastCheckTime: null,
        
        init() {
            // Load initial notifications
            this.loadNotifications();
            
            // Start polling for new notifications every 30 seconds
            this.startPolling();
            
            // Listen for custom events
            window.addEventListener('notification-received', (e) => {
                this.addNotification(e.detail);
            });
            
            // Listen for page visibility changes (pause polling when tab is hidden)
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    this.stopPolling();
                } else {
                    this.startPolling();
                    this.loadNotifications(); // Refresh when tab becomes visible
                }
            });
        },
        
        async loadNotifications() {
            try {
                const response = await fetch('/api/notifications/unread', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });
                
                if (!response.ok) {
                    return;
                }
                
                const data = await response.json();
                
                if (data.success && data.notifications) {
                    // Only add new notifications (not already in the list)
                    data.notifications.forEach(notif => {
                        if (!this.notifications.find(n => n.id === notif.id)) {
                            this.addNotification(notif);
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        },
        
        addNotification(notification) {
            // Don't add if already exists
            if (this.notifications.find(n => n.id === notification.id)) {
                return;
            }
            
            // Add to the beginning of the array
            this.notifications.unshift({
                ...notification,
                visible: true,
                time_ago: this.formatTimeAgo(notification.created_at)
            });
            
            // Auto-dismiss after 8 seconds (longer than regular toast)
            setTimeout(() => {
                this.dismiss(notification.id);
            }, 8000);
            
            // Play notification sound (optional)
            this.playNotificationSound();
            
            // Update badge count in navbar
            this.updateBadgeCount();
        },
        
        dismiss(notificationId) {
            const notification = this.notifications.find(n => n.id === notificationId);
            if (notification) {
                notification.visible = false;
                
                // Remove from array after animation
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== notificationId);
                }, 200);
                
                // Mark as read on server
                this.markAsRead(notificationId);
            }
        },
        
        async markAsRead(notificationId) {
            try {
                await fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    credentials: 'same-origin'
                });
                
                // Update local state
                const notification = this.notifications.find(n => n.id === notificationId);
                if (notification) {
                    notification.is_read = true;
                }
                
                // Update badge count
                this.updateBadgeCount();
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },
        
        updateBadgeCount() {
            // Dispatch event to update navbar badge
            const unreadCount = this.notifications.filter(n => !n.is_read && n.visible).length;
            window.dispatchEvent(new CustomEvent('notification-count-updated', { 
                detail: { count: unreadCount } 
            }));
        },
        
        formatTimeAgo(timestamp) {
            const now = new Date();
            const time = new Date(timestamp);
            const diff = Math.floor((now - time) / 1000);
            
            if (diff < 60) return 'Baru saja';
            if (diff < 3600) return `${Math.floor(diff / 60)} menit yang lalu`;
            if (diff < 86400) return `${Math.floor(diff / 3600)} jam yang lalu`;
            return `${Math.floor(diff / 86400)} hari yang lalu`;
        },
        
        startPolling() {
            if (this.pollingInterval) {
                return;
            }
            
            this.pollingInterval = setInterval(() => {
                this.loadNotifications();
            }, 30000); // Poll every 30 seconds
        },
        
        stopPolling() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
                this.pollingInterval = null;
            }
        },
        
        playNotificationSound() {
            // Optional: Play a subtle notification sound
            // You can add an audio file and play it here
        }
    };
}
</script>
@endauth

