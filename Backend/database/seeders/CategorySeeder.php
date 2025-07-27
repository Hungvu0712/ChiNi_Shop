<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Áo', 'description' => 'Danh mục áo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Quần', 'description' => 'Danh mục quần', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Túi xách', 'description' => 'Danh mục túi xách', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
