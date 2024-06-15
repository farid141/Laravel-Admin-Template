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
        Permission::create(['name' => 'view book']);
        Permission::create(['name' => 'viewAny book']);
        Permission::create(['name' => 'create book']);
        Permission::create(['name' => 'update book']);
        Permission::create(['name' => 'delete book']);
        // $role = Role::create(['name' => 'librarian']);
        // $permission = Permission::create(['name' => 'edit articles']);

        // $role->givePermissionTo($permission);
    }
}
