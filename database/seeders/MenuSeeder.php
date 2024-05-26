<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::firstOrCreate(
            ['name' => 'members', 'order' => 1, 'icon' => 'test']
        );
        Menu::firstOrCreate(
            ['name' => 'items', 'order' => 2, 'icon' => 'test']
        );
    }
}
