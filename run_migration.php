<?php

/**
 * Run the it_requests migration directly
 * Usage: php run_migration.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

echo "Starting migration...\n";
echo "Database connection: " . DB::getDefaultConnection() . "\n";
echo "Database name: " . DB::connection()->getDatabaseName() . "\n\n";

try {
    // Check if table already exists
    if (Schema::hasTable('it_requests')) {
        echo "✓ Table 'it_requests' already exists.\n";
    } else {
        echo "Creating table 'it_requests'...\n";
        
        Schema::create('it_requests', function (Blueprint $table) {
            $table->id('requestID');
            $table->date('requestDate');
            $table->string('title');
            $table->text('requestDesc');
            $table->string('status');
            $table->string('assetID')->nullable();
            $table->string('requesterID');
            $table->string('approverID')->nullable();
            $table->timestamps();

            $table->foreign('assetID')->references('assetID')->on('assets')->onDelete('set null');
            $table->foreign('requesterID')->references('userID')->on('users')->onDelete('cascade');
            $table->foreign('approverID')->references('userID')->on('users')->onDelete('set null');
        });
        
        echo "✓ Table 'it_requests' created successfully!\n";
    }
    
    // Check and add migration record
    $migrationName = '2025_11_18_222623_create_it_requests_table';
    $exists = DB::table('migrations')->where('migration', $migrationName)->exists();
    
    if (!$exists) {
        $batch = DB::table('migrations')->max('batch') ?? 0;
        $batch++;
        
        DB::table('migrations')->insert([
            'migration' => $migrationName,
            'batch' => $batch
        ]);
        
        echo "✓ Migration record added.\n";
    } else {
        echo "✓ Migration record already exists.\n";
    }
    
    echo "\nMigration completed successfully!\n";
    
} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "\nFile: " . $e->getFile() . ":" . $e->getLine() . "\n";
    if ($e->getPrevious()) {
        echo "Previous: " . $e->getPrevious()->getMessage() . "\n";
    }
    exit(1);
}

