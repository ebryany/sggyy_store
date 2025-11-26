@php
    $isFromMe = $message->isFromCurrentUser();
    $alignment = $isFromMe ? 'justify-end' : 'justify-start';
    $bgColor = $isFromMe ? 'bg-primary text-white' : 'bg-white/10 text-white';
@endphp

<div class="flex {{ $alignment }} mb-4" data-message-id="{{ $message->id }}">
    <div class="max-w-[75%] sm:max-w-[60%] lg:max-w-[50%]">
        @if(!$isFromMe)
        <div class="flex items-center gap-2 mb-1 px-1">
            <img src="{{ $message->sender->avatar ? asset('storage/' . $message->sender->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($message->sender->name) }}" 
                 alt="{{ $message->sender->name }}" 
                 class="w-5 h-5 rounded-full border border-white/20">
            <span class="text-xs text-white/60 font-medium">{{ $message->sender->name }}</span>
        </div>
        @endif
        
        <div class="p-3 sm:p-4 rounded-xl shadow-lg {{ $bgColor }} {{ $isFromMe ? 'rounded-tr-sm' : 'rounded-tl-sm' }}">
            @if($message->message)
            <p class="text-sm sm:text-base whitespace-pre-wrap break-words leading-relaxed">{{ $message->message }}</p>
            @endif
            
            @if($message->attachment_path)
            <div class="mt-3">
                @php
                    $attachmentUrl = $message->getAttachmentUrl();
                    $extension = strtolower(pathinfo($message->attachment_path, PATHINFO_EXTENSION));
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                @endphp
                
                @if($isImage)
                <div class="rounded-lg overflow-hidden border-2 border-white/20">
                    <a href="{{ $attachmentUrl }}" target="_blank">
                        <img src="{{ $attachmentUrl }}" 
                             alt="Attachment" 
                             class="max-w-full h-auto max-h-64 object-contain">
                    </a>
                </div>
                @else
                <a href="{{ $attachmentUrl }}" 
                   target="_blank" 
                   class="inline-flex items-center gap-2 px-3 py-2 {{ $isFromMe ? 'bg-white/20 hover:bg-white/30' : 'bg-white/10 hover:bg-white/20' }} rounded-lg transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>{{ basename($message->attachment_path) }}</span>
                </a>
                @endif
            </div>
            @endif
            
            <div class="flex items-center {{ $isFromMe ? 'justify-end' : 'justify-start' }} gap-2 mt-2">
                <span class="text-xs opacity-70">{{ $message->created_at->format('H:i') }}</span>
                @if($isFromMe)
                    @if($message->is_read)
                    <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    @else
                    <svg class="w-4 h-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

