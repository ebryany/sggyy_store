<?php

namespace App\Console\Commands;

use App\Services\EscrowService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessEscrowAutoRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'escrow:auto-release {--limit=100 : Maximum escrows to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-release escrows that have passed hold period';

    /**
     * Execute the console command.
     */
    public function handle(EscrowService $escrowService): int
    {
        $this->info('🔄 Processing escrow auto-release...');
        
        $limit = (int) $this->option('limit');
        $escrows = $escrowService->getEscrowsReadyForAutoRelease($limit);
        
        if ($escrows->isEmpty()) {
            $this->info('✅ No escrows ready for auto-release');
            return 0;
        }
        
        $this->info("Found {$escrows->count()} escrow(s) ready for auto-release");
        $this->newLine();
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($escrows as $escrow) {
            try {
                $escrowService->autoRelease($escrow);
                $successCount++;
                
                $this->line("✅ Released escrow #{$escrow->id} for order #{$escrow->order->order_number}");
                
                Log::info('Escrow auto-released', [
                    'escrow_id' => $escrow->id,
                    'order_id' => $escrow->order_id,
                    'hold_until' => $escrow->hold_until?->toDateTimeString(),
                ]);
            } catch (\Exception $e) {
                $errorCount++;
                
                $this->error("❌ Failed to release escrow #{$escrow->id}: {$e->getMessage()}");
                
                Log::error('Escrow auto-release failed', [
                    'escrow_id' => $escrow->id,
                    'order_id' => $escrow->order_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        $this->newLine();
        $this->info("✅ Successfully released: {$successCount}");
        if ($errorCount > 0) {
            $this->warn("❌ Failed: {$errorCount}");
        }
        
        return $errorCount > 0 ? 1 : 0;
    }
}

