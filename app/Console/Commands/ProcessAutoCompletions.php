<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OrderService;

class ProcessAutoCompletions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-auto-completions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process auto-completions for orders waiting buyer confirmation';

    /**
     * Execute the console command.
     */
    public function handle(OrderService $orderService)
    {
        $this->info('Processing auto-completions...');
        
        $completedCount = $orderService->processAutoCompletions();
        
        if ($completedCount > 0) {
            $this->info("✅ Auto-completed {$completedCount} order(s) after buyer confirmation window expired.");
        } else {
            $this->info('✅ No orders pending auto-completion.');
        }
        
        return Command::SUCCESS;
    }
}
