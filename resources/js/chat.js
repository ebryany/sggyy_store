/**
 * Chat Module - AJAX & Real-time Implementation
 * 
 * Features:
 * - Send messages without page reload
 * - Real-time message updates via Laravel Echo
 * - Auto-scroll to bottom
 * - File attachment support
 * - Typing indicators
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Initialize Pusher & Laravel Echo
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    wsHost: import.meta.env.VITE_PUSHER_HOST || `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }
});

class ChatHandler {
    constructor() {
        this.chatForm = document.getElementById('chat-form');
        this.messageInput = document.getElementById('message-input');
        this.attachmentInput = document.getElementById('attachment-input');
        this.messagesContainer = document.getElementById('messages-container');
        this.sendButton = document.getElementById('send-button');
        this.chatId = this.messagesContainer?.dataset.chatId;
        this.currentUserId = this.messagesContainer?.dataset.currentUserId;
        this.username = this.messagesContainer?.dataset.username;
        
        this.init();
    }

    init() {
        if (!this.chatForm) return;

        // Handle form submit
        this.chatForm.addEventListener('submit', (e) => this.handleSubmit(e));

        // Auto-resize textarea
        if (this.messageInput) {
            this.messageInput.addEventListener('input', () => this.autoResizeTextarea());
        }

        // File attachment preview
        if (this.attachmentInput) {
            this.attachmentInput.addEventListener('change', () => this.handleFileSelect());
        }

        // Join chat room for real-time updates
        if (this.chatId) {
            this.joinChatRoom();
        }

        // Scroll to bottom on load
        this.scrollToBottom();
    }

    async handleSubmit(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent any other handlers

        const message = this.messageInput.value.trim();
        const attachment = this.attachmentInput?.files[0];

        // Validate
        if (!message && !attachment) {
            this.showError('Pesan atau file attachment wajib diisi');
            return;
        }

        // Disable button
        this.setLoading(true);

        // Prepare form data
        const formData = new FormData();
        if (message) formData.append('message', message);
        if (attachment) formData.append('attachment', attachment);

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found');
            this.showError('CSRF token tidak ditemukan');
            this.setLoading(false);
            return;
        }

        try {
            const response = await fetch(this.chatForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData
            });

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Response is not JSON:', contentType);
                this.showError('Response tidak valid dari server');
                this.setLoading(false);
                return;
            }

            const result = await response.json();

            if (response.ok && result.success) {
                // Clear form
                this.messageInput.value = '';
                this.autoResizeTextarea();
                if (this.attachmentInput) this.attachmentInput.value = '';
                this.removeFilePreview();

                // Append message to chat (optimistic UI update)
                if (result.data) {
                    this.appendMessage(result.data, true);
                }

                // Scroll to bottom
                this.scrollToBottom();

                // Show success (optional, message already appended)
                // this.showSuccess('Pesan berhasil dikirim');
            } else {
                this.showError(result.message || 'Gagal mengirim pesan');
            }
        } catch (error) {
            console.error('Error sending message:', error);
            this.showError('Terjadi kesalahan saat mengirim pesan: ' + error.message);
        } finally {
            this.setLoading(false);
        }
    }

    appendMessage(data, isFromMe = false) {
        const messageHtml = this.createMessageHtml(data, isFromMe);
        
        if (this.messagesContainer) {
            this.messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
        }
    }

    createMessageHtml(data, isFromMe) {
        const alignment = isFromMe ? 'justify-end' : 'justify-start';
        const bgColor = isFromMe ? 'bg-pink-500 text-white' : 'bg-gray-200 dark:bg-gray-700';
        
        return `
            <div class="flex ${alignment} mb-4" data-message-id="${data.id}">
                <div class="max-w-xs lg:max-w-md">
                    ${!isFromMe ? `<p class="text-xs text-gray-500 dark:text-gray-400 mb-1">${data.sender_name}</p>` : ''}
                    <div class="${bgColor} rounded-lg p-3 shadow">
                        ${data.message ? `<p class="text-sm">${this.escapeHtml(data.message)}</p>` : ''}
                        ${data.attachment_url ? this.createAttachmentHtml(data.attachment_url) : ''}
                        <p class="text-xs ${isFromMe ? 'text-pink-100' : 'text-gray-500'} mt-1">
                            ${data.created_at}
                            ${isFromMe ? '✓' : ''}
                        </p>
                    </div>
                </div>
            </div>
        `;
    }

    createAttachmentHtml(url) {
        const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(url);
        
        if (isImage) {
            return `<img src="${url}" alt="Attachment" class="max-w-full rounded mt-2 mb-2">`;
        }
        
        return `
            <a href="${url}" target="_blank" class="flex items-center gap-2 text-sm underline mt-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                View Attachment
            </a>
        `;
    }

    joinChatRoom() {
        if (!window.Echo) {
            console.warn('Laravel Echo not initialized');
            return;
        }

        window.Echo.private(`chat.${this.chatId}`)
            .listen('.message.sent', (e) => {
                console.log('New message received:', e);
                
                // Check if message is from other user
                if (e.sender && e.sender.id != this.currentUserId) {
                    this.appendMessage(e, false);
                    this.scrollToBottom();
                    this.playNotificationSound();
                }
            })
            .error((error) => {
                console.error('Echo connection error:', error);
            });

        console.log(`Joined chat room: chat.${this.chatId}`);
    }

    scrollToBottom() {
        if (this.messagesContainer) {
            this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
        }
    }

    autoResizeTextarea() {
        if (this.messageInput) {
            this.messageInput.style.height = 'auto';
            this.messageInput.style.height = (this.messageInput.scrollHeight) + 'px';
        }
    }

    handleFileSelect() {
        const file = this.attachmentInput.files[0];
        if (file) {
            this.showFilePreview(file);
        }
    }

    showFilePreview(file) {
        const preview = document.getElementById('file-preview');
        if (preview) {
            preview.innerHTML = `
                <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    <span>${file.name}</span>
                    <button type="button" onclick="chatHandler.removeFilePreview()" class="text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            preview.classList.remove('hidden');
        }
    }

    removeFilePreview() {
        if (this.attachmentInput) {
            this.attachmentInput.value = '';
        }
        const preview = document.getElementById('file-preview');
        if (preview) {
            preview.classList.add('hidden');
            preview.innerHTML = '';
        }
    }

    setLoading(isLoading) {
        if (this.sendButton) {
            this.sendButton.disabled = isLoading;
            if (isLoading) {
                this.sendButton.innerHTML = '<svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            } else {
                this.sendButton.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>';
            }
        }

        if (this.messageInput) {
            this.messageInput.disabled = isLoading;
        }
    }

    showSuccess(message) {
        this.showToast(message, 'success');
    }

    showError(message) {
        this.showToast(message, 'error');
    }

    showToast(message, type = 'info') {
        // Simple toast implementation
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    playNotificationSound() {
        // Optional: play notification sound
        const audio = new Audio('/sounds/notification.mp3');
        audio.volume = 0.3;
        audio.play().catch(() => {
            // Ignore if sound fails to play
        });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.chatHandler = new ChatHandler();
    });
} else {
    window.chatHandler = new ChatHandler();
}

export default ChatHandler;

