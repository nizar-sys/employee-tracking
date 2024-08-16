<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $corePermission = Permission::create(['name' => 'all_permissions']);

        $adminRole = Role::create(['name' => 'Administrator']);
        $adminRole->givePermissionTo($corePermission);

        $adminUser = User::create([
            'name' => 'Administrator',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $adminUser->assignRole($adminRole);

        $permissions = [
            'User Management',
            'user_management_permission_read',
            'user_management_permission_create',
            'user_management_permission_update',
            'user_management_permission_delete',
            'user_management_role_read',
            'user_management_role_create',
            'user_management_role_update',
            'user_management_role_delete',
            'user_management_user_read',
            'user_management_user_create',
            'user_management_user_update',
            'user_management_user_delete',
        ];

        $defaultPermissions = array_map(function ($permission) {
            return [
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $permissions);

        Permission::insert($defaultPermissions);
    }
}
