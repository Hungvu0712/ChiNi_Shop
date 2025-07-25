<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Variant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 1 cart cho user_id = 1
        $cart = Cart::create([
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Lấy 5 variant đầu tiên (mỗi sản phẩm có 2 biến thể → lấy mỗi sp 1 cái)
        $variants = Variant::with('product')->take(5)->get();

        foreach ($variants as $variant) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $variant->product_id,
                'variant_id' => $variant->id,
                'quantity' => rand(1, 3),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✅ Đã seed 1 cart và 5 cart_items.\n";
    }
}
