<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $i) {
            // Tạo đơn hàng mới
            $orderId = DB::table('orders')->insertGetId([
                'user_id' => 1,
                'address_id' => 1,
                'voucher_id' => 1,
                'code' => strtoupper($faker->bothify('ORD###??')),
                'total_price' => 0, // sẽ cập nhật sau
                'shipping_fee' => 15000,
                'discount_amount' => $faker->numberBetween(0, 50000),
                'payment_method' => $faker->randomElement(['COD', 'bank_transfer']),
                'payment_status' => $faker->randomElement(['paid', 'unpaid']),
                'order_status' => $faker->randomElement(['processing', 'shipping', 'completed']),
                'note' => $faker->sentence(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $total = 0;

            // Tạo 1-3 order_items
            foreach (range(1, $faker->numberBetween(1, 3)) as $j) {
                $quantity = $faker->numberBetween(1, 5);

                // Giới hạn giá tiền sao cho total không vượt quá 999,999.99
                $maxPricePerItem = intval(999999 / $quantity);

                // Giá tiền nguyên, trong khoảng từ 100000 đến maxPricePerItem
                $price = $faker->numberBetween(100000, max(100000, $maxPricePerItem));

                $totalItem = $quantity * $price;

                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => 1,
                    'variant_id' => 1,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $totalItem,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $total += $totalItem;
            }

            // Cập nhật tổng tiền đơn hàng
            DB::table('orders')->where('id', $orderId)->update([
                'total_price' => $total,
            ]);
        }
    }
}
