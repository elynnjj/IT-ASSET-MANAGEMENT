<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'userID' => 'ITDept',
            'fullName' => 'IT Department',
            'email' => 'syaz.elyna@gmail.com',
            'password' => 'ITDept123',
            'department' => 'IT',
            'role' => 'ITDept',
            'accStat' => 'active'
        ]);
    }
}
