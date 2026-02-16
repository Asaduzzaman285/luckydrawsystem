<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@luckydraw.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        // Create Agent
        $agent = User::firstOrCreate(
            ['email' => 'agent@luckydraw.com'],
            [
                'name' => 'System Agent',
                'password' => Hash::make('password'),
            ]
        );
        $agent->assignRole('agent');

        // Create Regular User
        $user = User::firstOrCreate(
            ['email' => 'user@luckydraw.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole('user');

        // Create Super Admin (if needed)
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@luckydraw.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole('super-admin');
    }
}
