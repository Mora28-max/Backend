<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // List of all permissions grouped by module
        $permissions = [
            'admin add members',
            'admin remove members',
            'admin change roles',
            'admin view members',
            'customers create',
            'customers view',
            'customers update',
            'view payments',
            'delete payments',
            'view remaining payments',
            'create remaining payments',
            'update remaining payments',
            'delete remaining payments',
            'view monthly charges',
            'collect monthly charges',
            'waive monthly charges',
            'discount monthly charges',
            'reports create',
            'reports view',
            'reports update',
            'reports delete',
            'notifications create',
            'notifications view',
            'notifications update',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Define roles and assign appropriate permissions
        $roles = [
            'Admin' => $permissions,
            'Technical Assistant' => [
                'customers view',
                'reports create',
                'reports view',
                'reports update',
                'view remaining payments',
                'create remaining payments',
            ],
            'Cashier' => [
                'admin view members',
                'customers view',
                'delete payments',
                'collect monthly charges',
                'view monthly charges',
                'view payments',
                'reports create',
                'reports view',
                'view remaining payments',
                'create remaining payments',
                'update remaining payments',
                'delete remaining payments',
            ],
            'Notifier' => [
                'admin view members',
                'customers view',
                'customers create',
                'customers update',
                'notifications create',
                'notifications view',
                'notifications update',
                'reports create',
                'reports view',
                'reports update',
                'collect monthly charges',
                'view monthly charges',
                'view payments',
                'reports create',
                'reports view',
                'view remaining payments',
                'create remaining payments',
                'update remaining payments',
                'delete remaining payments',
            ],
            'Plumber' => [
                'customers view',
                'reports view',
                'reports update',
                'view remaining payments',
                'create remaining payments',
            ],
            'Technical Coordinator' => [
                'customers view',
                'reports create',
                'reports view',
                'reports update',
                'view remaining payments',
                'create remaining payments',
            ],
            'General Assistant A' => $permissions,
            'General Assistant B' => [], // to be defined later
            'General Administrator' => $permissions,
            'Developer' => $permissions,
        ];

        // Create roles and sync their permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
