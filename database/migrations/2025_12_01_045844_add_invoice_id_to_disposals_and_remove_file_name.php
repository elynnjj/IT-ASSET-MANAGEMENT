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
        Schema::table('disposals', function (Blueprint $table) {
            // Add invoiceID column
            $table->unsignedBigInteger('invoiceID')->nullable()->after('assetID');
            $table->foreign('invoiceID')->references('invoiceID')->on('invoices')->onDelete('set null');
            
            // Remove fileName column if it exists
            if (Schema::hasColumn('disposals', 'fileName')) {
                $table->dropColumn('fileName');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposals', function (Blueprint $table) {
            // Drop foreign key and invoiceID column
            $table->dropForeign(['invoiceID']);
            $table->dropColumn('invoiceID');
            
            // Add back fileName column
            $table->string('fileName')->nullable()->after('dispDate');
        });
    }
};
