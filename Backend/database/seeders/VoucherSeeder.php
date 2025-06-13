<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('vouchers')->insert([
            [
                'code' => 'FREESHIP2025',
                'title' => 'Miễn phí vận chuyển cho đơn hàng từ 500k',
                'voucher_type' => 'freeship',
                'discount_type' => 'amount',
                'value' => 0,
                'max_discount_value' => 0.00,
                'min_order_value' => 500000.00,
                'limit' => 100,
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'is_active' => 1,
            ],
            [
                'code' => 'GIAM10TRIEU',
                'title' => 'Giảm 10% tối đa 1 triệu',
                'voucher_type' => 'discount',
                'discount_type' => 'percent',
                'value' => 10,
                'max_discount_value' => 999999.99, // chỉnh xuống dưới 1 triệu
                'min_order_value' => 1000.00,
                'limit' => 50,
                'start_date' => now(),
                'end_date' => now()->addDays(15),
                'is_active' => 1,
            ],
            [
                'code' => 'FIX50K',
                'title' => 'Giảm trực tiếp 50k cho đơn từ 300k',
                'voucher_type' => 'discount',
                'discount_type' => 'amount',
                'value' => 50000.00,
                'max_discount_value' => 50000.00,
                'min_order_value' => 300000.00,
                'limit' => 200,
                'start_date' => now(),
                'end_date' => now()->addDays(10),
                'is_active' => 1,
            ],
        ]);
    }
}
