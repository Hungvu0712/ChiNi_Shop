<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AddressesTableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 11; $i++) {
            DB::table('addresses')->insert([
                'user_id' => $i,
                'fullname' => "Người nhận $i",
                'phone' => "090000000$i",
                'address' => "Hồ Chí Minh, Quận $i, Phường Bến Nghé",
                'specific_address' => "Số nhà $i, đường ABC",
                'note' => 'Giao giờ hành chính',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
