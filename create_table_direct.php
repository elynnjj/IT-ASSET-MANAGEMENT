<?php

/**
 * Direct script to create it_requests table
 * Run: php create_table_direct.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    // Check if table exists
    if (Schema::hasTable('it_requests')) {
        echo "Table 'it_requests' already exists.\n";
        exit(0);
    }

    // Create the table using Schema
    Schema::create('it_requests', function ($table) {
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

    echo "Table 'it_requests' created successfully!\n";
    
    // Also record it in migrations table
    $migrationName = '2025_11_18_222623_create_it_requests_table';
    $batch = DB::table('migrations')->max('batch') ?? 0;
    $batch++;
    
    DB::table('migrations')->insert([
        'migration' => $migrationName,
        'batch' => $batch
    ]);
    
    echo "Migration record added to migrations table.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

