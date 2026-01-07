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
        Schema::table('assign_assets', function (Blueprint $table) {
            $table->string('userFullName')->nullable()->after('userID');
            $table->string('userDepartment')->nullable()->after('userFullName');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assign_assets', function (Blueprint $table) {
            $table->dropColumn(['userFullName', 'userDepartment']);
        });
    }
};

