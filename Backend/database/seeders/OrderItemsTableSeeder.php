<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;

class OrderItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();

        foreach ($orders as $order) {
            $itemsCount = rand(1, 4);

            for ($i = 0; $i < $itemsCount; $i++) {
                $product = Product::inRandomOrder()->first();

                $variant = $product->variants()->inRandomOrder()->first();

                $quantity = rand(1, 3);
                $price = rand(10000, 300000); // Tối đa 300,000 để tránh vượt quá total
                $total = $price * $quantity;

                // Nếu vượt giới hạn decimal(8,2) thì skip
                if ($total > 999999.99) {
                    $i--;
                    continue;
                }

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'quantity'   => $quantity,
                    'price'      => $price,
                    'total'      => $total,
                ]);
            }
        }
    }
}
