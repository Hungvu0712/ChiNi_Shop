<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('attribute_values')->insert([
            ['attribute_id' => 1, 'value' => 'S', 'created_at' => now(), 'updated_at' => now()],
            ['attribute_id' => 1, 'value' => 'M', 'created_at' => now(), 'updated_at' => now()],
            ['attribute_id' => 2, 'value' => 'Red', 'created_at' => now(), 'updated_at' => now()],
            ['attribute_id' => 2, 'value' => 'Blue', 'created_at' => now(), 'updated_at' => now()],
            ['attribute_id' => 3, 'value' => 'Cotton', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
