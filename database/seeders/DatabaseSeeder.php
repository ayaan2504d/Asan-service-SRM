<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::updateOrCreate(
            ['email' => 'ayanjafri150@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Ayan42101'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Staff User
        User::updateOrCreate(
            ['email' => 'ayan2504d@aptechsite.net'],
            [
                'name' => 'Staff User',
                'password' => Hash::make('ayan42101'),
                'role' => 'staff',
                'is_active' => true,
            ]
        );
    }
}
