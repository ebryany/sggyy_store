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
        Schema::create('order_progress_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // seller/admin who updated
            $table->integer('progress_from')->default(0);
            $table->integer('progress_to');
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable(); // File attachment (draft, screenshot, etc.)
            $table->timestamps();
            
            // Indexes
            $table->index(['order_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_progress_updates');
    }
};
