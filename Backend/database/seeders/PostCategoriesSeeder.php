<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $postCategories = [
            [
                'name' => 'Tin tức',
                'description' => 'Danh mục tin tức mới nhất',
            ],
            [
                'name' => 'Khuyến mãi',
                'description' => 'Tổng hợp các chương trình khuyến mãi',
            ],
            [
                'name' => 'Hướng dẫn',
                'description' => 'Bài viết hướng dẫn sử dụng sản phẩm',
            ],
        ];

        foreach ($postCategories as $pc) {
            DB::table('post_categories')->insert([
                'name' => $pc['name'],
                'slug' => \Str::slug($pc['name']), 
                'description' => $pc['description'],
            ]);
        }
    }
}
