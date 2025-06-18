<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $title = "Bài viết số $i";
            Post::create([
                'post_category_id' => rand(1, 3), // thay bằng id danh mục có sẵn
                'title' => $title,
                'slug' => \Str::slug($title),
                'excerpt' => "Tóm tắt bài viết số $i",
                'content' => "Đây là nội dung chi tiết cho bài viết số $i. Nội dung có thể chứa HTML hoặc văn bản dài.",
                'featured_image' => "images/featured_$i.jpg",
                'public_featured_image_id' => "public_id_$i",
                'status' => rand(0, 1) ? 'published' : 'draft',
            ]);
        }
    }
}
