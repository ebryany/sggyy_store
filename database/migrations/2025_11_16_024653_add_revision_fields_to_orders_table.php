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
        Schema::table('orders', function (Blueprint $table) {
            // Revision fields for service orders
            $table->boolean('needs_revision')->default(false)->after('deliverable_path');
            $table->integer('revision_count')->default(0)->after('needs_revision');
            $table->text('revision_notes')->nullable()->after('revision_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['needs_revision', 'revision_count', 'revision_notes']);
        });
    }
};
