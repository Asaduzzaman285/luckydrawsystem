<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Define Permissions
        $permissions = [
            'create-draw',
            'select-winner',
            'approve-withdrawal',
            'deposit-to-user',
            'view-reports',
            'manage-users',
            'system-settings',
            'suspend-account',
            'view-analytics',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Define Roles and Assign Permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions($permissions);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(array_diff($permissions, ['system-settings']));

        $agent = Role::firstOrCreate(['name' => 'agent']);
        $agent->syncPermissions([
            'deposit-to-user',
            'view-reports',
            'suspend-account',
            'view-analytics',
        ]);

        $user = Role::firstOrCreate(['name' => 'user']);
    }
}