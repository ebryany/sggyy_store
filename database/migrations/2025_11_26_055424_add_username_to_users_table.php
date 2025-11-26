<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add username column as nullable first
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name')->nullable();
            $table->index('username');
        });

        // Step 2: Generate username for existing users
        if (DB::table('users')->count() > 0) {
            DB::table('users')->orderBy('id')->chunk(100, function ($users) {
                foreach ($users as $user) {
                    // Skip if username already exists
                    if (!empty($user->username)) {
                        continue;
                    }
                    
                    $baseUsername = Str::slug($user->name ?? 'user', '_');
                    if (empty($baseUsername)) {
                        $baseUsername = 'user';
                    }
                    
                    $username = $baseUsername;
                    $counter = 1;

                    // Ensure unique username
                    while (DB::table('users')->where('username', $username)->where('id', '!=', $user->id)->exists()) {
                        $username = $baseUsername . '_' . $counter;
                        $counter++;
                    }

                    DB::table('users')->where('id', $user->id)->update(['username' => $username]);
                }
            });
        }

        // Step 3: Make username non-nullable after populating
        // Use raw SQL for better compatibility across database drivers
        DB::statement('ALTER TABLE users MODIFY username VARCHAR(255) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['username']);
            $table->dropColumn('username');
        });
    }
};
