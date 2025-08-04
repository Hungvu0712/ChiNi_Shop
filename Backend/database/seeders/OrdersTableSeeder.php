<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Address;
use App\Models\PaymentMethod;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'Đang chờ xác nhận',
            'Đã xác nhận',
            'Đã hủy',
            'Đang vận chuyển',
            'Giao hàng thành công',
            'Hoàn thành',
        ];

        $paymentStatus = ['Đã thanh toán', 'Chưa Thanh Toán'];

        $userIds = User::pluck('id')->toArray();
        $paymentMethodIds = PaymentMethod::pluck('id')->toArray();

        if (empty($userIds) || empty($paymentMethodIds)) {
            $this->command->warn("⚠️ Không có user hoặc payment method để tạo đơn hàng.");
            return;
        }

        for ($i = 0; $i < 50; $i++) {
            $createdAt = Carbon::now()->subDays(rand(0, 180));

            $userId = $userIds[array_rand($userIds)];
            $paymentMethodId = $paymentMethodIds[array_rand($paymentMethodIds)];

            $address = Address::where('user_id', $userId)->where('is_default', 1)->first();

            if (!$address) {
                $this->command->warn("⚠️ User ID $userId không có địa chỉ mặc định.");
                continue;
            }

            $total = min(rand(100000, 2000000), 999999.99);
            $shippingFee = min(rand(10000, 50000), 999999.99);
            $voucherDiscount = min(rand(0, 200000), 999999.99);
            $quantity = rand(1, 5);

            DB::table('orders')->insert([
                'user_id'              => $userId,
                'voucher_id'           => null,
                'payment_method_id'    => $paymentMethodId,
                'order_status'         => $statuses[array_rand($statuses)],
                'payment_status'       => $paymentStatus[array_rand($paymentStatus)],
                'order_code'           => 'ORD' . strtoupper(Str::random(8)),
                'total_quantity'       => $quantity,
                'total'                => $total,
                'user_name'            => $address->name ?? 'Khách hàng',
                'user_email'           => $address->email ?? 'guest@example.com',
                'user_phonenumber'     => $address->phone_number ?? '0123456789',
                'user_address'         => $address->address . ', ' . ($address->specific_address ?? ''),
                'user_note'            => 'Đơn hàng thử nghiệm',
                'ship_user_name'       => $address->name ?? null,
                'ship_user_phonenumber' => $address->phone_number ?? null,
                'ship_user_address'    => $address->address . ', ' . ($address->specific_address ?? ''),
                'shipping_fee'         => $shippingFee,
                'voucher_discount'     => $voucherDiscount,
                'created_at'           => $createdAt,
                'updated_at'           => $createdAt,
            ]);
        }
    }
}
