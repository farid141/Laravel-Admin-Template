<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Submenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::firstOrCreate([
            'name' => 'Members',
            'order' => 1,
            'icon' => 'test',
            'has_child' => false,
            'url' => '/members',
        ]);
        Menu::firstOrCreate([
            'name' => 'Items',
            'order' => 2,
            'icon' => 'test',
            'has_child' => true
        ]);

        Submenu::firstOrCreate([
            'menu_id' => 2,
            'name' => 'Books',
            'url' => '/books',
            'order' => 1
        ]);
        Submenu::firstOrCreate([
            'menu_id' => 2,
            'name' => 'Disks',
            'url' => '/disks',
            'order' => 2
        ]);
    }
}
