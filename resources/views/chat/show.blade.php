@extends('layouts.app')

@section('title', 'Chat dengan ' . $otherUser->name . ' - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-4xl">
    <!-- Chat Header -->
    <div class="glass p-4 sm:p-6 rounded-xl mb-4 border border-white/10">
        <div class="flex items-center gap-4">
            <a href="{{ route('chat.index') }}" 
               class="p-2 glass glass-hover rounded-lg transition-colors">
                <x-icon name="arrow-left" class="w-5 h-5" />
            </a>
            
            <div class="relative flex-shrink-0">
                <img src="{{ $otherUser->avatar ? asset('storage/' . $otherUser->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) }}" 
                     alt="{{ $otherUser->name }}" 
                     class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border-2 border-white/10">
                @if($otherUser->updated_at && $otherUser->updated_at->gt(now()->subMinutes(5)))
                <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-gray-900 rounded-full"></span>
                @endif
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-semibold truncate">{{ $otherUser->name }}</h1>
                    @if($otherUser->isSeller() || $otherUser->isAdmin())
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    @endif
                </div>
                @if($otherUser->updated_at && $otherUser->updated_at->gt(now()->subMinutes(5)))
                <p class="text-sm text-green-400 flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    Sedang Aktif
                </p>
                @elseif($otherUser->updated_at)
                <p class="text-sm text-white/60">Terakhir aktif {{ $otherUser->updated_at->diffForHumans() }}</p>
                @endif
            </div>

            @if($otherUser->store_slug)
            <a href="{{ route('store.show', $otherUser->store_slug) }}" 
               class="px-4 py-2 glass glass-hover rounded-lg border border-primary/30 bg-primary/20 text-primary hover:bg-primary/30 hover:border-primary/50 transition-all font-semibold text-sm flex items-center gap-2">
                <x-icon name="arrow-right" class="w-4 h-4" />
                <span class="hidden sm:inline">Toko</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Messages Container -->
    <div class="glass p-4 sm:p-6 rounded-xl mb-4 border border-white/10" 
         id="messages-container" 
         data-chat-id="{{ $chat->id }}"
         data-current-user-id="{{ $currentUser->id }}"
         data-username="{{ $otherUser->username }}"
         style="max-height: 60vh; overflow-y: auto; scroll-behavior: smooth;">
        @if($messages->isEmpty())
        <div class="text-center py-12">
            <x-icon name="chat" class="w-16 h-16 mx-auto mb-4 text-white/40" />
            <p class="text-white/60 text-lg font-semibold mb-2">Belum ada pesan</p>
            <p class="text-white/40 text-sm">Mulai percakapan dengan mengirim pesan pertama!</p>
        </div>
        @else
        @foreach($messages as $message)
            @include('chat.partials.message', ['message' => $message])
        @endforeach
        @endif
    </div>

    <!-- File Preview Container -->
    <div id="file-preview" class="hidden mb-4 glass p-3 rounded-xl border border-white/10"></div>

    <!-- Message Form -->
    <form id="chat-form" 
          action="{{ route('chat.send', '@' . $otherUser->username) }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="glass p-4 sm:p-6 rounded-xl border border-white/10">
        @csrf
        
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- File Input (Hidden) -->
            <input type="file" 
                   name="attachment" 
                   id="attachment-input" 
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt,.jpg,.jpeg,.png,.gif,.webp"
                   class="hidden">
            
            <!-- Attachment Button -->
            <button type="button" 
                    onclick="document.getElementById('attachment-input').click()"
                    class="px-4 py-3 glass glass-hover rounded-lg transition-colors flex-shrink-0 flex items-center justify-center">
                <x-icon name="file-text" class="w-5 h-5" />
            </button>
            
            <!-- Message Input -->
            <textarea name="message" 
                      id="message-input"
                      rows="1"
                      placeholder="Ketik pesan..." 
                      class="flex-1 px-4 py-3 glass border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary resize-none"></textarea>
            
            <!-- Send Button -->
            <button type="submit" 
                    id="send-button"
                    class="px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg font-semibold transition-colors flex-shrink-0 flex items-center justify-center shadow-lg hover:shadow-primary/20 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- Chat module will be loaded via app.js -->
<script>
// Listen for chat module initialization
document.addEventListener('DOMContentLoaded', () => {
    console.log('Chat page loaded with username: @{{ $otherUser->username }}');
});
</script>
@endpush
