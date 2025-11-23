<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Service;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->index('slug');
        });

        // Generate slugs for existing services
        Service::chunk(100, function ($services) {
            foreach ($services as $service) {
                if (empty($service->slug)) {
                    $slug = Str::slug($service->title);
                    $originalSlug = $slug;
                    $counter = 1;
                    
                    // Ensure uniqueness
                    while (Service::where('slug', $slug)->where('id', '!=', $service->id)->exists()) {
                        $slug = $originalSlug . '-' . $counter;
                        $counter++;
                    }
                    
                    $service->update(['slug' => $slug]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};
