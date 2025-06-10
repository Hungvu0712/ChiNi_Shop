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
        $parentMenu = Menu::create([
            'name' => 'Trang chủ',
            'slug' => 'trang-chu',
            'url' => '/',
        ]);

        Menu::create([
            'name' => 'Giới thiệu',
            'slug' => 'gioi-thieu',
            'url' => '/gioi-thieu',
        ]);

        Menu::create([
            'name' => 'Liên hệ',
            'slug' => 'lien-he',
            'url' => '/lien-he',
        ]);
    }
}
