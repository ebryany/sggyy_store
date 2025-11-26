/**
 * Escrow Real-time Updates Module
 * 
 * Features:
 * - Real-time escrow status updates via Laravel Echo
 * - Auto-refresh escrow card when status changes
 * - Show notifications for escrow events
 */

// Only initialize if Echo is available
if (typeof window.Echo !== 'undefined') {
    class EscrowHandler {
        constructor() {
            this.currentOrderId = null;
            this.currentUserId = null;
            this.init();
        }

        init() {
            // Get order ID from page (if on order detail page)
            const orderIdMatch = window.location.pathname.match(/\/orders\/([^\/]+)/);
            if (orderIdMatch) {
                // Try to get order ID from data attribute or route
                const orderElement = document.querySelector('[data-order-id]');
                if (orderElement) {
                    this.currentOrderId = orderElement.getAttribute('data-order-id');
                }
            }

            // Get current user ID
            const userMeta = document.querySelector('meta[name="user-id"]');
            if (userMeta) {
                this.currentUserId = userMeta.getAttribute('content');
            }

            if (this.currentUserId) {
                this.listenToUserChannel();
            }

            if (this.currentOrderId) {
                this.listenToOrderChannel();
            }

            console.log('✅ EscrowHandler initialized', {
                orderId: this.currentOrderId,
                userId: this.currentUserId
            });
        }

        listenToUserChannel() {
            // Listen to user-specific channel for all escrow events
            window.Echo.private(`user.${this.currentUserId}`)
                .listen('.escrow.created', (data) => {
                    console.log('🔒 Escrow created:', data);
                    this.handleEscrowCreated(data);
                })
                .listen('.escrow.released', (data) => {
                    console.log('✅ Escrow released:', data);
                    this.handleEscrowReleased(data);
                })
                .listen('.escrow.disputed', (data) => {
                    console.log('⚠️ Escrow disputed:', data);
                    this.handleEscrowDisputed(data);
                })
                .listen('.escrow.refunded', (data) => {
                    console.log('💰 Escrow refunded:', data);
                    this.handleEscrowRefunded(data);
                });
        }

        listenToOrderChannel() {
            // Listen to order-specific channel (if on order detail page)
            window.Echo.private(`order.${this.currentOrderId}`)
                .listen('.escrow.created', (data) => {
                    this.handleEscrowCreated(data);
                })
                .listen('.escrow.released', (data) => {
                    this.handleEscrowReleased(data);
                })
                .listen('.escrow.disputed', (data) => {
                    this.handleEscrowDisputed(data);
                })
                .listen('.escrow.refunded', (data) => {
                    this.handleEscrowRefunded(data);
                });
        }

        handleEscrowCreated(data) {
            if (this.currentOrderId && data.order_id == this.currentOrderId) {
                this.refreshEscrowCard();
                this.showNotification('🔒 Escrow dibuat', 'Dana telah ditahan di escrow untuk pesanan #' + data.order_number);
            }
        }

        handleEscrowReleased(data) {
            if (this.currentOrderId && data.order_id == this.currentOrderId) {
                this.refreshEscrowCard();
                this.showNotification('✅ Escrow dilepas', 'Dana telah dilepas untuk pesanan #' + data.order_number);
            }
        }

        handleEscrowDisputed(data) {
            if (this.currentOrderId && data.order_id == this.currentOrderId) {
                this.refreshEscrowCard();
                this.showNotification('⚠️ Dispute dibuat', 'Dispute telah dibuat untuk pesanan #' + data.order_number, 'warning');
            }
        }

        handleEscrowRefunded(data) {
            if (this.currentOrderId && data.order_id == this.currentOrderId) {
                this.refreshEscrowCard();
                this.showNotification('💰 Dana dikembalikan', 'Dana telah dikembalikan untuk pesanan #' + data.order_number);
            }
        }

        refreshEscrowCard() {
            // Reload the escrow card section
            const escrowCard = document.querySelector('[data-escrow-card]');
            if (escrowCard) {
                // Trigger a page refresh or AJAX reload
                // For now, just reload the page to show updated status
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // If no escrow card found, just reload page
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        }

        showNotification(title, message, type = 'success') {
            // Create a toast notification
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 glass p-4 rounded-lg border border-white/20 shadow-lg max-w-sm animate-slide-in`;
            notification.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        ${type === 'warning' ? '⚠️' : type === 'error' ? '❌' : '✅'}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm mb-1">${title}</h4>
                        <p class="text-xs text-white/70">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white/60 hover:text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(notification);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        window.escrowHandler = new EscrowHandler();
    });
} else {
    console.warn('⚠️ Laravel Echo not available. Escrow real-time updates disabled.');
}

