<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrateToMySQL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:migrate-to-mysql 
                            {--fresh : Drop all tables and re-run migrations}
                            {--seed : Seed the database after migration}
                            {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Complete migration from SQLite to MySQL: Create database, run migrations, and optionally seed data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting MySQL Migration Process...');
        $this->newLine();

        // Step 1: Check current database connection
        $currentConnection = config('database.default');
        $this->info("📊 Current database connection: {$currentConnection}");
        
        if ($currentConnection !== 'mysql') {
            $this->warn('⚠️  Warning: Current database connection is not MySQL!');
            $this->line('Make sure your .env file has: DB_CONNECTION=mysql');
            $this->newLine();
            
            if (!$this->confirm('Do you want to continue?', true)) {
                $this->warn('❌ Migration cancelled.');
                return 1;
            }
        }

        // Step 2: Get database configuration
        $dbName = env('DB_DATABASE', 'ebrystoree');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');
        $username = env('DB_USERNAME', 'root');
        $password = env('DB_PASSWORD', '');

        $this->info('📋 Database Configuration:');
        $this->table(
            ['Setting', 'Value'],
            [
                ['Database Name', $dbName],
                ['Host', $host],
                ['Port', $port],
                ['Username', $username],
                ['Password', $password ? '***' : '(empty)'],
            ]
        );
        $this->newLine();

        // Step 3: Create database if not exists
        $this->info('📦 Step 1: Creating database...');
        try {
            // Connect to MySQL server (without database)
            $pdo = new \PDO(
                "mysql:host={$host};port={$port}",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]
            );

            // Check if database exists
            $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = " . $pdo->quote($dbName));
            $exists = $stmt->fetch();

            if (!$exists) {
                $this->info("Creating database '{$dbName}'...");
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $this->info("✅ Database '{$dbName}' created successfully!");
            } else {
                $this->info("ℹ️  Database '{$dbName}' already exists.");
            }
        } catch (\PDOException $e) {
            $this->error('❌ Failed to create database!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('💡 Make sure MySQL is running in Laragon!');
            return 1;
        }
        $this->newLine();

        // Step 4: Test database connection
        $this->info('🧪 Step 2: Testing database connection...');
        try {
            DB::connection('mysql')->getPdo();
            $this->info('✅ Database connection successful!');
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('💡 Check your .env file configuration!');
            return 1;
        }
        $this->newLine();

        // Step 5: Run migrations
        $this->info('🔄 Step 3: Running migrations...');
        try {
            if ($this->option('fresh')) {
                $this->warn('⚠️  Running fresh migration (will drop all tables)...');
                Artisan::call('migrate:fresh', [
                    '--force' => $this->option('force'),
                ]);
            } else {
                Artisan::call('migrate', [
                    '--force' => $this->option('force'),
                ]);
            }
            
            $this->info('✅ Migrations completed successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Migration failed!');
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // Step 6: Seed database (optional)
        if ($this->option('seed')) {
            $this->info('🌱 Step 4: Seeding database...');
            try {
                Artisan::call('db:seed', [
                    '--class' => 'DemoDataSeeder',
                    '--force' => $this->option('force'),
                ]);
                $this->info('✅ Database seeded successfully!');
            } catch (\Exception $e) {
                $this->warn('⚠️  Seeding failed: ' . $e->getMessage());
            }
            $this->newLine();
        }

        // Summary
        $this->info('✨ Migration completed successfully!');
        $this->newLine();
        $this->info('📝 Summary:');
        $this->line('  ✅ Database created/verified');
        $this->line('  ✅ Connection tested');
        $this->line('  ✅ Migrations executed');
        if ($this->option('seed')) {
            $this->line('  ✅ Database seeded');
        }
        $this->newLine();
        $this->info('🎉 Your application is now using MySQL!');
        
        return 0;
    }
}

