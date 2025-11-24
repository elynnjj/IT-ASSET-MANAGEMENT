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
        Schema::create('assign_assets', function (Blueprint $table) {
            $table->id('assignID');
            $table->date('checkoutDate');
            $table->date('checkinDate')->nullable();
            $table->string('assetID');
            $table->string('userID');
            $table->timestamps();

            $table->foreign('assetID')->references('assetID')->on('assets')->onDelete('cascade');
            $table->foreign('userID')->references('userID')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assign_assets');
    }
};
