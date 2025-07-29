<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AddressesTableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('addresses')->insert([
                'user_id' => 1,
                'fullname' => 'Người nhận 1',
                'phone' => '0900000001',
                'address' => 'Hồ Chí Minh, Quận 1, Phường Bến Nghé',
                'specific_address' => 'Số nhà 1, đường ABC',
                'note' => 'Giao giờ hành chính',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
