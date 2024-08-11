<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view~Menu-Menu',
            'viewAny~Menu-Menu',
            'create~Menu-Menu',
            'update~Menu-Menu',
            'delete~Menu-Menu',

            'view~Menu-Submenu',
            'viewAny~Menu-Submenu',
            'create~Menu-Submenu',
            'update~Menu-Submenu',
            'delete~Menu-Submenu',

            'view~Access-Permission',
            'viewAny~Access-Permission',
            'create~Access-Permission',
            'update~Access-Permission',
            'delete~Access-Permission',

            'view~Access-Role',
            'viewAny~Access-Role',
            'create~Access-Role',
            'update~Access-Role',
            'delete~Access-Role',

            'view~User',
            'viewAny~User',
            'create~User',
            'update~User',
            'delete~User',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        Role::create(['name' => 'admin']);
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123')
        ]);
        $user->assignRole('admin');
    }
}
