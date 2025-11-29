<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    if (!Schema::hasTable('maintenances')) {
        echo "Table does not exist. Creating...\n";
        
        DB::statement("
            CREATE TABLE IF NOT EXISTS `maintenances` (
                `mainID` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
                `mainDate` date NOT NULL,
                `mainDesc` text NOT NULL,
                `assetID` varchar(255) NULL,
                `requestID` bigint UNSIGNED NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                `updated_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`mainID`),
                KEY `maintenances_assetid_foreign` (`assetID`),
                KEY `maintenances_requestid_foreign` (`requestID`),
                CONSTRAINT `maintenances_assetid_foreign` FOREIGN KEY (`assetID`) REFERENCES `assets` (`assetID`) ON DELETE SET NULL,
                CONSTRAINT `maintenances_requestid_foreign` FOREIGN KEY (`requestID`) REFERENCES `it_requests` (`requestID`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
        
        // Insert migration record
        $migrationName = '2025_11_18_222624_create_maintenances_table';
        if (!DB::table('migrations')->where('migration', $migrationName)->exists()) {
            $batch = DB::table('migrations')->max('batch') ?? 0;
            DB::table('migrations')->insert([
                'migration' => $migrationName,
                'batch' => $batch + 1,
            ]);
        }
        
        echo "Table created successfully!\n";
    } else {
        echo "Table already exists.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

