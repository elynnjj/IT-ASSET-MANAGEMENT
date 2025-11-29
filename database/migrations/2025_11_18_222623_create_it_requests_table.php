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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('it_requests');
    }
};

