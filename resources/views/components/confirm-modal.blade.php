@props(['id', 'title', 'message', 'confirmText' => 'Ya, Lanjutkan', 'cancelText' => 'Batal', 'type' => 'warning', 'formId' => null])

<div id="{{ $id }}" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
     style="display: none;"
     x-cloak
     onclick="
         if (event.target === this) {
             this.style.display = 'none';
             document.body.style.overflow = '';
         }
     ">
    <div class="glass p-4 sm:p-6 rounded-xl max-w-md w-full shadow-2xl border border-white/10"
         onclick="event.stopPropagation()">
        <div class="flex items-start gap-4 mb-4">
            <div class="flex-shrink-0">
                @if($type === 'warning')
                    <x-icon name="warning" class="w-8 h-8 text-yellow-400" />
                @elseif($type === 'danger')
                    <x-icon name="alert" class="w-8 h-8 text-red-400" />
                @elseif($type === 'info')
                    <x-icon name="info" class="w-8 h-8 text-blue-400" />
                @else
                    <x-icon name="check" class="w-8 h-8 text-green-400" />
                @endif
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold mb-2">{{ $title }}</h3>
                <p class="text-white/80 text-sm sm:text-base">{{ $message }}</p>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <button type="button"
                    onclick="
                        const modal = document.getElementById('{{ $id }}');
                        if (modal) {
                            modal.style.display = 'none';
                            document.body.style.overflow = '';
                        }
                    "
                    class="px-4 py-2.5 glass glass-hover rounded-lg font-semibold transition-colors order-2 sm:order-1">
                {{ $cancelText }}
            </button>
            <button type="button"
                    id="{{ $id }}-confirm-btn"
                    onclick="
                        const modal = document.getElementById('{{ $id }}');
                        @if($formId)
                        const form = document.getElementById('{{ $formId }}');
                        if (form) {
                            form.submit();
                            return;
                        }
                        @endif
                        // Fallback: close modal
                        if (modal) {
                            modal.style.display = 'none';
                            document.body.style.overflow = '';
                        }
                    "
                    class="px-4 py-2.5 rounded-lg font-semibold transition-colors order-1 sm:order-2
                    @if($type === 'danger') bg-red-500 hover:bg-red-600 text-white
                    @elseif($type === 'warning') bg-yellow-500 hover:bg-yellow-600 text-white
                    @elseif($type === 'info') bg-blue-500 hover:bg-blue-600 text-white
                    @else bg-primary hover:bg-primary-dark text-white
                    @endif">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>

