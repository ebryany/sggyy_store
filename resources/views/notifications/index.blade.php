@extends('layouts.app')

@section('title', 'Notifikasi - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-4xl">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-500/20 backdrop-blur-lg flex items-center justify-center border border-purple-500/30 relative">
                <x-icon name="bell" class="w-6 h-6 text-purple-400" />
                @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1 bg-primary text-white text-xs rounded-full min-w-[20px] h-[20px] flex items-center justify-center font-bold border-2 border-dark">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
                @endif
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold">Notifikasi</h1>
                <p class="text-sm text-white/60">
                    @if($unreadCount > 0)
                        {{ $unreadCount }} belum dibaca
                    @else
                        Semua notifikasi sudah dibaca
                    @endif
                </p>
            </div>
        </div>
        
        @if($unreadCount > 0)
        <form action="{{ route('notifications.readAll') }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                    class="px-4 py-2 bg-primary/20 hover:bg-primary/30 text-primary rounded-lg font-semibold transition-colors text-sm border border-primary/30">
                Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    <!-- Notifications List -->
    @if($notifications->isEmpty())
    <div class="glass p-12 sm:p-16 rounded-xl text-center border border-white/10">
        <div class="flex justify-center mb-6">
            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full bg-white/5 border-2 border-white/10 flex items-center justify-center">
                <x-icon name="bell" class="w-12 h-12 sm:w-16 sm:h-16 text-white/30" />
            </div>
        </div>
        <h3 class="text-xl sm:text-2xl font-semibold mb-2 text-white">Tidak ada notifikasi</h3>
        <p class="text-white/60 mb-8 text-sm sm:text-base">Anda belum memiliki notifikasi</p>
        <a href="{{ route('dashboard') }}" 
           class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg font-semibold transition-all hover:scale-105 shadow-lg shadow-primary/20">
            Kembali ke Dashboard
        </a>
    </div>
    @else
    <div class="space-y-3">
        @foreach($notifications as $notification)
        <div class="glass glass-hover p-4 sm:p-6 rounded-xl transition-all hover:scale-[1.01] border {{ !$notification->is_read ? 'border-l-4 border-primary bg-primary/5' : 'border-white/10' }} group">
            <div class="flex items-start gap-4">
                <!-- Icon based on type -->
                <div class="flex-shrink-0 w-10 h-10 rounded-lg {{ !$notification->is_read ? 'bg-primary/20 border border-primary/30' : 'bg-white/10 border border-white/10' }} flex items-center justify-center">
                    @if($notification->type === 'order_created')
                        <x-icon name="shopping-bag" class="w-5 h-5 {{ !$notification->is_read ? 'text-primary' : 'text-white/60' }}" />
                    @elseif($notification->type === 'payment_verified')
                        <x-icon name="check" class="w-5 h-5 {{ !$notification->is_read ? 'text-primary' : 'text-white/60' }}" />
                    @elseif($notification->type === 'order_message')
                        <x-icon name="message-square" class="w-5 h-5 {{ !$notification->is_read ? 'text-primary' : 'text-white/60' }}" />
                    @elseif($notification->type === 'progress_milestone')
                        <x-icon name="chart" class="w-5 h-5 {{ !$notification->is_read ? 'text-primary' : 'text-white/60' }}" />
                    @elseif($notification->type === 'new_order')
                        <x-icon name="wallet" class="w-5 h-5 {{ !$notification->is_read ? 'text-primary' : 'text-white/60' }}" />
                    @elseif($notification->type === 'chat_message')
                        <x-icon name="chat" class="w-5 h-5 {{ !$notification->is_read ? 'text-primary' : 'text-white/60' }}" />
                    @elseif($notification->type === 'deadline_passed')
                        <x-icon name="clock" class="w-5 h-5 {{ !$notification->is_read ? 'text-primary' : 'text-white/60' }}" />
                    @else
                        <x-icon name="bell" class="w-5 h-5 {{ !$notification->is_read ? 'text-primary' : 'text-white/60' }}" />
                    @endif
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm sm:text-base {{ !$notification->is_read ? 'text-white' : 'text-white/90' }} mb-1 break-words">
                        {{ $notification->message }}
                    </p>
                    <div class="flex items-center justify-between gap-2 mt-2">
                        <p class="text-xs text-white/60">{{ $notification->created_at->diffForHumans() }}</p>
                        @if(!$notification->is_read)
                        <form action="{{ route('notifications.read', $notification) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="text-xs px-3 py-1 bg-primary/20 hover:bg-primary/30 text-primary rounded-lg transition-colors">
                                Tandai Dibaca
                            </button>
                        </form>
                        @endif
                    </div>
                    
                    <!-- Link to related item if available -->
                    @if($notification->notifiable_id && $notification->notifiable_type)
                    <div class="mt-3">
                        @if($notification->notifiable_type === 'App\Models\Order' && $notification->notifiable)
                        <a href="{{ route('orders.show', $notification->notifiable) }}" 
                           class="inline-flex items-center gap-2 text-xs px-3 py-1.5 glass glass-hover rounded-lg transition-all hover:scale-105 border border-white/10">
                            <x-icon name="arrow-right" class="w-4 h-4" />
                            Lihat Pesanan
                        </a>
                        @elseif($notification->notifiable_type === 'App\Models\Chat' && $notification->notifiable)
                        @php
                            $otherUser = $notification->notifiable->getOtherUser();
                        @endphp
                        @if($otherUser)
                        <a href="{{ route('chat.show', $otherUser->id) }}" 
                           class="inline-flex items-center gap-2 text-xs px-3 py-1.5 glass glass-hover rounded-lg transition-all hover:scale-105 border border-white/10">
                            <x-icon name="arrow-right" class="w-4 h-4" />
                            Buka Chat
                        </a>
                        @endif
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Read indicator -->
                @if($notification->is_read)
                <div class="flex-shrink-0">
                    <x-icon name="check" class="w-5 h-5 text-green-400" />
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

