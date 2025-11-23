<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OrderService;

class ProcessPaymentTimeouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-payment-timeouts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process payment timeouts and auto-cancel expired pending payments';

    /**
     * Execute the console command.
     */
    public function handle(OrderService $orderService)
    {
        $this->info('Processing payment timeouts...');
        
        $cancelledCount = $orderService->processPaymentTimeouts();
        
        if ($cancelledCount > 0) {
            $this->info("✅ Cancelled {$cancelledCount} order(s) due to payment timeout.");
        } else {
            $this->info('✅ No expired payments found.');
        }
        
        return Command::SUCCESS;
    }
}
