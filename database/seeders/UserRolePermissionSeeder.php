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
            'view book',
            'viewAny book',
            'create book',
            'update book',
            'delete book'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $role = Role::create(['name' => 'writer']);
        $role->givePermissionTo($permissions);

        $user = User::create([
            'name' => 'farid',
            'email' => 'farid@gmail.com',
            'password' => Hash::make('12345')
        ]);
        $user->assignRole('writer');
    }
}
