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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id('mainID');
            $table->date('mainDate');
            $table->text('mainDesc');
            $table->string('assetID')->nullable();
            $table->unsignedBigInteger('requestID')->nullable();
            $table->timestamps();

            $table->foreign('assetID')->references('assetID')->on('assets')->onDelete('set null');
            $table->foreign('requestID')->references('requestID')->on('it_requests')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};

