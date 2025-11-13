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
        Schema::create('assets', function (Blueprint $table) {
            $table->string('assetID')->primary();
            $table->string('assetType');
            $table->string('serialNum')->nullable();
            $table->string('model')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->date('purchaseDate')->nullable();
            $table->string('osVer')->nullable();
            $table->string('processor')->nullable();
            $table->string('status')->nullable();
            $table->text('installedSoftware')->nullable();
            $table->string('invoiceID')->nullable();
            $table->timestamps();

            $table->foreign('invoiceID')->references('invoiceID')->on('invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
