<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Address;
use App\Models\User;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['Đã giao', 'Đang xử lý', 'Đã hủy'];
        $paymentStatus = ['Đã thanh toán', 'Chưa thanh toán'];
        $paymentMethods = ['COD', 'VNPAY', 'MOMO'];

        $addressIds = Address::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        if (empty($addressIds) || empty($userIds)) {
            $this->command->warn("⚠️ Không có user hoặc address để tạo đơn hàng.");
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $createdAt = Carbon::now()->subDays(rand(0, 180));

            $totalPrice = min(rand(100000, 2000000), 999999.99);
            $shippingFee = min(rand(10000, 50000), 999999.99);
            $discountAmount = min(rand(0, 200000), 999999.99);

            DB::table('orders')->insert([
                'user_id' => $userIds[array_rand($userIds)],
                'address_id' => $addressIds[array_rand($addressIds)],
                'voucher_id' => null,
                'code' => 'ORD' . strtoupper(Str::random(8)),
                'total_price' => $totalPrice,
                'shipping_fee' => $shippingFee,
                'discount_amount' => $discountAmount,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_status' => $paymentStatus[array_rand($paymentStatus)],
                'order_status' => $statuses[array_rand($statuses)],
                'note' => 'Đơn hàng thử nghiệm',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
