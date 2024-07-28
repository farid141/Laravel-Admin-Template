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
            'name' => 'members',
            'order' => 1,
            'icon' => 'test',
            'has_child' => true
        ]);
        Menu::firstOrCreate([
            'name' => 'items',
            'order' => 2,
            'icon' => 'test',
            'has_child' => true
        ]);

        Submenu::firstOrCreate([
            'menu_id' => 1,
            'name' => 'members',
            'url' => '/members',
            'order' => 1
        ]);
        Submenu::firstOrCreate([
            'menu_id' => 2,
            'name' => 'books',
            'url' => '/books',
            'order' => 1
        ]);
        Submenu::firstOrCreate([
            'menu_id' => 2,
            'name' => 'disks',
            'url' => '/disks',
            'order' => 2
        ]);
    }
}
