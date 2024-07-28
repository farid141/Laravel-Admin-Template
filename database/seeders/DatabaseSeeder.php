<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BookSeeder::class,
            DiskSeeder::class,
            MemberSeeder::class,
            MenuSeeder::class,
            UserRolePermissionSeeder::class,
        ]);
    }
}
