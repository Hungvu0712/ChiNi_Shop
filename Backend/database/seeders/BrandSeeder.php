<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->insert([
            [
                'name' => 'Gucci',
                'brand_image' => 'gucci.jpg',
                'public_brand_image_id' => 'gucci_123',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Nike',
                'brand_image' => 'nike.jpg',
                'public_brand_image_id' => 'nike_456',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
