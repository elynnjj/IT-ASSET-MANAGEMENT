<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\AssignAsset;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill user information for existing assignment records
        $assignments = AssignAsset::whereNull('userFullName')
            ->orWhereNull('userDepartment')
            ->get();

        foreach ($assignments as $assignment) {
            $user = User::find($assignment->userID);
            
            if ($user) {
                $assignment->userFullName = $user->fullName;
                $assignment->userDepartment = $user->department;
                $assignment->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this data migration
    }
};

