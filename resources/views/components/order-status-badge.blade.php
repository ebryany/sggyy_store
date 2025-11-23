@props(['status'])

@php
    $statusConfig = [
        'pending' => ['bg' => 'bg-yellow-500/20', 'text' => 'text-yellow-400', 'border' => 'border-yellow-500/30', 'label' => 'Pending'],
        'paid' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/30', 'label' => 'Paid'],
        'processing' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/30', 'label' => 'Processing'],
        'completed' => ['bg' => 'bg-green-500/20', 'text' => 'text-green-400', 'border' => 'border-green-500/30', 'label' => 'Completed'],
        'cancelled' => ['bg' => 'bg-red-500/20', 'text' => 'text-red-400', 'border' => 'border-red-500/30', 'label' => 'Cancelled'],
        'needs_revision' => ['bg' => 'bg-orange-500/20', 'text' => 'text-orange-400', 'border' => 'border-orange-500/30', 'label' => 'Perlu Revisi'],
    ];
    
    $config = $statusConfig[$status] ?? ['bg' => 'bg-white/10', 'text' => 'text-white/60', 'border' => 'border-white/20', 'label' => ucfirst($status ?? 'Unknown')];
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} border {{ $config['border'] }}">
    {{ $config['label'] }}
</span>

