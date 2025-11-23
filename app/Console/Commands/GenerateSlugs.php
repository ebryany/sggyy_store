<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Console\Command;

class GenerateSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:generate-slugs 
                            {--products : Only generate slugs for products}
                            {--services : Only generate slugs for services}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for products and services that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Generating slugs for products and services...');
        $this->newLine();

        $generateProducts = !$this->option('services');
        $generateServices = !$this->option('products');

        if ($generateProducts) {
            $this->generateProductSlugs();
        }

        if ($generateServices) {
            $this->generateServiceSlugs();
        }

        $this->newLine();
        $this->info('✨ Slug generation completed!');
        
        return 0;
    }

    private function generateProductSlugs(): void
    {
        $this->info('📦 Generating slugs for products...');
        
        $products = Product::whereNull('slug')
            ->orWhere('slug', '')
            ->get();
        
        if ($products->isEmpty()) {
            $this->info('  ✅ All products already have slugs.');
            return;
        }

        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        foreach ($products as $product) {
            $product->slug = Product::generateSlug($product->title, $product->id);
            $product->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("  ✅ Generated slugs for {$products->count()} products.");
    }

    private function generateServiceSlugs(): void
    {
        $this->info('🛍️  Generating slugs for services...');
        
        $services = Service::whereNull('slug')
            ->orWhere('slug', '')
            ->get();
        
        if ($services->isEmpty()) {
            $this->info('  ✅ All services already have slugs.');
            return;
        }

        $bar = $this->output->createProgressBar($services->count());
        $bar->start();

        foreach ($services as $service) {
            $service->slug = Service::generateSlug($service->title, $service->id);
            $service->save();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("  ✅ Generated slugs for {$services->count()} services.");
    }
}

