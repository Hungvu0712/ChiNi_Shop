<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

         $permissions = [
            'brand-list',
            'brand-create',
            'brand-edit',
            'category-list',
            'category-create',
            'category-edit',
            'category-create',
            'category-edit',
            'permssion-list',
            'permssion-create',
            'permssion-edit',
            'role-create',
            'role-edit',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'brand-delete',
            'category-delete',
            'permssion-delete',
            'role-list',
            'role-delete',
            'profile-show',
            'dashboard',
            'role-assign',
            'user-assign',
         ];

         foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Tạo role admin và gán tất cả permission
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // Gán role admin cho user đầu tiên
        $user = \App\Models\User::first();
        if ($user) {
            $user->assignRole($admin);
        }
    }
}
