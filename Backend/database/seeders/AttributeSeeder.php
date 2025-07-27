<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('attributes')->insert([
            ['name' => 'Size', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Color', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chất liệu', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
