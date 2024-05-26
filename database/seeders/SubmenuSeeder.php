<?php

namespace Database\Seeders;

use App\Models\Submenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubmenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createSubMenu(['menu_id' => 1, 'name' => 'members', 'url' => '/members', 'order' => 1]);
        $this->createSubMenu(['menu_id' => 2, 'name' => 'books', 'url' => '/books', 'order' => 1]);
        $this->createSubMenu(['menu_id' => 2, 'name' => 'disks', 'url' => '/disks', 'order' => 2]);
    }

    private function createSubMenu($data)
    {
        Submenu::firstOrCreate($data);
    }
}
