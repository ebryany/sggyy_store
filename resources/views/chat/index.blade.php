@extends('layouts.app')

@section('title', 'Pesan - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-6xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold">💬 Pesan Saya</h1>
        <a href="{{ route('products.index') }}" 
           class="px-4 py-2 bg-primary hover:bg-primary/90 rounded-lg font-semibold transition-colors text-sm">
            Cari Produk
        </a>
    </div>

    @if($chats->isEmpty())
    <div class="glass p-8 sm:p-12 rounded-xl text-center border border-white/10">
        <svg class="w-16 h-16 mx-auto mb-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <h3 class="text-xl font-semibold mb-2">Belum ada pesan</h3>
        <p class="text-white/60 mb-6">Mulai chat dengan seller untuk bertanya tentang produk atau jasa</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($chats as $chat)
        <a href="{{ route('chat.show', '@' . $chat->other_user->username) }}" 
           class="block glass glass-hover p-4 sm:p-6 rounded-xl transition-all hover:scale-[1.01] border border-white/10 hover:border-primary/40 group">
            <div class="flex items-center gap-4">
                <!-- Avatar -->
                <div class="relative flex-shrink-0">
                    <img src="{{ $chat->other_user->avatar ? asset('storage/' . $chat->other_user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($chat->other_user->name) }}" 
                         alt="{{ $chat->other_user->name }}" 
                         class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border-2 border-white/10 group-hover:border-primary transition-colors">
                    @if($chat->unread_count > 0)
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center text-xs font-bold">
                        {{ $chat->unread_count > 9 ? '9+' : $chat->unread_count }}
                    </span>
                    @endif
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="font-semibold text-base sm:text-lg truncate group-hover:text-primary transition-colors">
                            {{ $chat->other_user->name }}
                        </h3>
                        @if($chat->last_message_at)
                        <span class="text-xs text-white/40 flex-shrink-0 ml-2">
                            {{ $chat->last_message_at->diffForHumans() }}
                        </span>
                        @endif
                    </div>
                    
                    @if($chat->messages->isNotEmpty())
                    <p class="text-sm text-white/60 truncate">
                        {{ $chat->messages->first()->message ?? '📎 File attachment' }}
                    </p>
                    @else
                    <p class="text-sm text-white/40 italic">Belum ada pesan</p>
                    @endif
                </div>

                <!-- Arrow -->
                <svg class="w-5 h-5 text-white/40 group-hover:text-primary group-hover:translate-x-1 transition-all flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>
@endsection

