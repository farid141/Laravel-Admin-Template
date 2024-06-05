<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'writer']);
        $permission = Permission::create(['name' => 'edit articles']);

        $role->givePermissionTo($permission);
        // $user = User::find(1);
        // $user->assignRole('writer');
    }
}
