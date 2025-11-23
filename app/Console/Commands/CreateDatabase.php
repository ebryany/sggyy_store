<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create 
                            {--name= : Database name (optional, will use from .env if not provided)}
                            {--host= : Database host (optional, default: 127.0.0.1)}
                            {--port= : Database port (optional, default: 3306)}
                            {--username= : Database username (optional, default: root)}
                            {--password= : Database password (optional)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create MySQL database for Laragon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🗄️  Creating MySQL database...');
        $this->newLine();

        // Get database configuration
        $dbName = $this->option('name') ?: env('DB_DATABASE', 'ebrystoreee');
        $host = $this->option('host') ?: env('DB_HOST', '127.0.0.1');
        $port = $this->option('port') ?: env('DB_PORT', '3306');
        $username = $this->option('username') ?: env('DB_USERNAME', 'root');
        $password = $this->option('password') ?: env('DB_PASSWORD', '');

        // Display configuration
        $this->info('📋 Configuration:');
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

        // Confirm before proceeding
        if (!$this->confirm('Do you want to create this database?', true)) {
            $this->warn('❌ Database creation cancelled.');
            return 1;
        }

        try {
            // Create temporary connection without database name
            $tempConfig = [
                'driver' => 'mysql',
                'host' => $host,
                'port' => $port,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ];

            // Connect to MySQL server (without database)
            $this->info('🔌 Connecting to MySQL server...');
            $pdo = new \PDO(
                "mysql:host={$host};port={$port}",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ]
            );

            $this->info('✅ Connected to MySQL server successfully!');
            $this->newLine();

            // Check if database already exists
            $this->info("🔍 Checking if database '{$dbName}' exists...");
            $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = " . $pdo->quote($dbName));
            $exists = $stmt->fetch();

            if ($exists) {
                if ($this->confirm("⚠️  Database '{$dbName}' already exists. Do you want to drop and recreate it?", false)) {
                    $this->warn("🗑️  Dropping existing database '{$dbName}'...");
                    $pdo->exec("DROP DATABASE IF EXISTS `{$dbName}`");
                    $this->info("✅ Database dropped successfully!");
                } else {
                    $this->info("ℹ️  Database '{$dbName}' already exists. Skipping creation.");
                    return 0;
                }
            }

            // Create database
            $this->info("📦 Creating database '{$dbName}'...");
            $charset = 'utf8mb4';
            $collation = 'utf8mb4_unicode_ci';
            
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$charset} COLLATE {$collation}");
            
            $this->info("✅ Database '{$dbName}' created successfully!");
            $this->newLine();

            // Test connection to the new database
            $this->info("🧪 Testing connection to database '{$dbName}'...");
            $testPdo = new \PDO(
                "mysql:host={$host};port={$port};dbname={$dbName}",
                $username,
                $password,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]
            );
            
            $this->info("✅ Connection test successful!");
            $this->newLine();

            // Display next steps
            $this->info('📝 Next steps:');
            $this->line('1. Make sure your .env file has the following configuration:');
            $this->newLine();
            $this->line('   DB_CONNECTION=mysql');
            $this->line("   DB_HOST={$host}");
            $this->line("   DB_PORT={$port}");
            $this->line("   DB_DATABASE={$dbName}");
            $this->line("   DB_USERNAME={$username}");
            $this->line("   DB_PASSWORD={$password}");
            $this->newLine();
            $this->line('2. Run migrations:');
            $this->line('   php artisan migrate');
            $this->newLine();
            $this->line('3. (Optional) Seed demo data:');
            $this->line('   php artisan db:seed --class=DemoDataSeeder');
            $this->newLine();

            $this->info('✨ Database setup completed successfully!');
            
            return 0;
        } catch (\PDOException $e) {
            $this->error('❌ Database creation failed!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('💡 Troubleshooting tips:');
            $this->line('1. Make sure MySQL/MariaDB is running in Laragon');
            $this->line('2. Check your MySQL credentials (username/password)');
            $this->line('3. Verify MySQL port (default: 3306)');
            $this->line('4. Check if MySQL service is accessible');
            $this->newLine();
            
            return 1;
        } catch (\Exception $e) {
            $this->error('❌ Unexpected error: ' . $e->getMessage());
            return 1;
        }
    }
}

