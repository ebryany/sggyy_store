<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Follower
            $table->foreignId('store_owner_id')->constrained('users')->onDelete('cascade'); // Store owner
            $table->timestamps();
            
            // Prevent duplicate follows
            $table->unique(['user_id', 'store_owner_id']);
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('store_owner_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_followers');
    }
};
