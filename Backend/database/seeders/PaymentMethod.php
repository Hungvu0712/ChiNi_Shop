<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethod extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('payment_methods')->insert([
            [
                "name" => "COD",
                "description" => "Thanh toán khi nhận hàng",

            ],
            [
                "name" => "VNPAY",
                "description" => "Thanh toán VNPAY",
            ]
        ]);
    }
}
