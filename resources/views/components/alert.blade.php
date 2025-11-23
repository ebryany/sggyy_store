@props(['type' => 'success', 'message' => null])

@php
    $alertConfig = [
        'success' => ['bg' => 'bg-green-500/20', 'border' => 'border-green-500/50', 'text' => 'text-green-400', 'icon' => '✓'],
        'error' => ['bg' => 'bg-red-500/20', 'border' => 'border-red-500/50', 'text' => 'text-red-400', 'icon' => '✕'],
        'warning' => ['bg' => 'bg-yellow-500/20', 'border' => 'border-yellow-500/50', 'text' => 'text-yellow-400', 'icon' => '⚠'],
        'info' => ['bg' => 'bg-blue-500/20', 'border' => 'border-blue-500/50', 'text' => 'text-blue-400', 'icon' => 'ℹ'],
    ];
    $config = $alertConfig[$type] ?? $alertConfig['success'];
@endphp

@php
    $hasMessage = false;
    $displayMessage = null;
    
    if ($message) {
        $hasMessage = true;
        $displayMessage = $message;
    } elseif (session($type)) {
        $hasMessage = true;
        $displayMessage = session($type);
    } elseif ($type === 'error' && $errors->any()) {
        // Only show errors in error alert, not in success alert
        $hasMessage = true;
        $displayMessage = $errors->first();
    }
@endphp

@if($hasMessage)
<div x-data="{ show: true }" 
     x-show="show" 
     x-transition 
     class="glass {{ $config['bg'] }} border {{ $config['border'] }} rounded-lg p-4 mb-4"
     data-alert-type="{{ $type }}"
     data-alert-message="{{ md5($displayMessage) }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <span class="{{ $config['text'] }} text-xl">{{ $config['icon'] }}</span>
            <p class="{{ $config['text'] }}">{{ $displayMessage }}</p>
        </div>
        <button x-on:click="show = false" class="{{ $config['text'] }} hover:opacity-70 touch-target">
            <x-icon name="x" class="w-5 h-5" />
        </button>
    </div>
</div>
@endif

<script>
// Prevent duplicate alerts on page load
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('[data-alert-type][data-alert-message]');
    const seen = new Set();
    
    alerts.forEach(alert => {
        const key = alert.getAttribute('data-alert-type') + '-' + alert.getAttribute('data-alert-message');
        if (seen.has(key)) {
            alert.remove();
        } else {
            seen.add(key);
        }
    });
});
</script>




