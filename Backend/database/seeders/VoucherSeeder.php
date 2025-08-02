<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vouchers')->insert([
            [
                'code' => 'FREESHIP2025',
                'title' => 'Miễn phí vận chuyển cho đơn hàng từ 500k',
                'voucher_type' => 'freeship',
                'discount_type' => 'none', // ✅ không để null
                'value' => 0,
                'max_discount_value' => 0.00, // ✅ không để null
                'min_order_value' => 500000.00,
                'limit' => 100,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'GIAM10TRIEU',
                'title' => 'Giảm 10% cho mọi đơn hàng từ 1.000đ',
                'voucher_type' => 'discount',
                'discount_type' => 'percent',
                'value' => 10,
                'max_discount_value' => 0.00, // ✅ không dùng nhưng vẫn hợp lệ
                'min_order_value' => 1000.00,
                'limit' => 50,
                'start_date' => now(),
                'end_date' => now()->addDays(15),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FIX50K',
                'title' => 'Giảm trực tiếp 50k cho đơn từ 300k',
                'voucher_type' => 'discount',
                'discount_type' => 'amount',
                'value' => 50000.00,
                'max_discount_value' => 0.00, // ✅ không dùng nhưng không gây lỗi
                'min_order_value' => 300000.00,
                'limit' => 200,
                'start_date' => now(),
                'end_date' => now()->addDays(10),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
