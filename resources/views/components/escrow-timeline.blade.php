@props(['escrow', 'order'])

@php
    $timeline = [];
    
    // Created
    if ($escrow->created_at) {
        $timeline[] = [
            'date' => $escrow->created_at,
            'status' => 'created',
            'label' => 'Escrow Dibuat',
            'description' => 'Dana sebesar Rp ' . number_format($escrow->amount, 0, ',', '.') . ' ditahan di escrow',
            'icon' => 'shield',
            'color' => 'blue',
        ];
    }
    
    // Holding
    if ($escrow->isHolding() && $escrow->hold_until) {
        $timeline[] = [
            'date' => $escrow->created_at,
            'status' => 'holding',
            'label' => 'Dana Ditahan',
            'description' => 'Dana akan dilepas pada ' . $escrow->hold_until->format('d M Y, H:i'),
            'icon' => 'clock',
            'color' => 'blue',
        ];
    }
    
    // Disputed
    if ($escrow->isDisputed() && $escrow->disputed_at) {
        $timeline[] = [
            'date' => $escrow->disputed_at,
            'status' => 'disputed',
            'label' => 'Dispute Dibuat',
            'description' => $escrow->dispute_reason ? Str::limit($escrow->dispute_reason, 100) : 'Dispute dibuat oleh ' . ($escrow->disputedBy ? $escrow->disputedBy->name : 'user'),
            'icon' => 'alert',
            'color' => 'orange',
        ];
    }
    
    // Released
    if ($escrow->isReleased() && $escrow->released_at) {
        $releaseTypeLabels = [
            'early' => 'dilepas lebih awal',
            'auto' => 'dilepas otomatis',
            'manual' => 'dilepas manual oleh admin',
        ];
        $releaseType = $releaseTypeLabels[$escrow->release_type] ?? 'dilepas';
        
        $timeline[] = [
            'date' => $escrow->released_at,
            'status' => 'released',
            'label' => 'Escrow Dilepas',
            'description' => 'Dana sebesar Rp ' . number_format($escrow->seller_earning ?? $escrow->amount, 0, ',', '.') . ' telah ' . $releaseType . ' ke seller',
            'icon' => 'check',
            'color' => 'green',
        ];
    }
    
    // Refunded
    if ($escrow->isRefunded()) {
        $timeline[] = [
            'date' => $escrow->updated_at,
            'status' => 'refunded',
            'label' => 'Dana Dikembalikan',
            'description' => 'Dana sebesar Rp ' . number_format($escrow->amount, 0, ',', '.') . ' telah dikembalikan ke buyer',
            'icon' => 'arrow-left',
            'color' => 'red',
        ];
    }
    
    // Sort by date
    usort($timeline, function($a, $b) {
        return $a['date']->timestamp <=> $b['date']->timestamp;
    });
@endphp

@if(count($timeline) > 0)
<div class="mt-4 pt-4 border-t border-white/10">
    <h4 class="text-sm font-semibold mb-4 flex items-center gap-2">
        <x-icon name="clock" class="w-4 h-4 text-primary" />
        Timeline Escrow
    </h4>
    
    <div class="space-y-4">
        @foreach($timeline as $index => $item)
        <div class="flex gap-3">
            <!-- Timeline Line -->
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center 
                    @if($item['color'] === 'blue') bg-blue-500/20 border border-blue-500/30 text-blue-400
                    @elseif($item['color'] === 'green') bg-green-500/20 border border-green-500/30 text-green-400
                    @elseif($item['color'] === 'orange') bg-orange-500/20 border border-orange-500/30 text-orange-400
                    @else bg-red-500/20 border border-red-500/30 text-red-400
                    @endif">
                    <x-icon name="{{ $item['icon'] }}" class="w-5 h-5" />
                </div>
                @if($index < count($timeline) - 1)
                <div class="w-0.5 h-full min-h-8 bg-white/10 mt-2"></div>
                @endif
            </div>
            
            <!-- Timeline Content -->
            <div class="flex-1 pb-4">
                <div class="flex items-start justify-between gap-2 mb-1">
                    <h5 class="font-semibold text-sm">{{ $item['label'] }}</h5>
                    <span class="text-xs text-white/60 whitespace-nowrap">
                        {{ $item['date']->format('d M Y, H:i') }}
                    </span>
                </div>
                <p class="text-xs text-white/70">{{ $item['description'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

