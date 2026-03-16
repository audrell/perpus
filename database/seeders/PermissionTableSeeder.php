<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'users.index',
            'users.create',
            'users.edit',
            'users.delete',
            'permissions.index',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',
            'roles.index',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'settings.index',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
