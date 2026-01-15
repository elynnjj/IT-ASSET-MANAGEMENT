<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ITDeptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['userID' => 'ITDept'],
            [
                'fullName' => 'IT Department',
                'email' => 'syaz.elyna@gmail.com',
                'password' => Hash::make('itdept@123'),
                'department' => 'IT',
                'role' => 'ITDept',
                'accStat' => 'active',
                'firstLogin' => false,
            ]
        );
    }
}
