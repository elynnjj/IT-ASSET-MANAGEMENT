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
        Schema::create('disposals', function (Blueprint $table) {
            $table->string('disposeID')->primary();
            $table->enum('dispStatus', ['pending', 'disposed'])->default('pending');
            $table->date('dispDate')->nullable();
            $table->string('assetID');
            $table->timestamps();

            $table->foreign('assetID')->references('assetID')->on('assets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposals');
    }
};

