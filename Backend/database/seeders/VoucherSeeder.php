<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class VoucherSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('vouchers')->insert([
                'code' => strtoupper(Str::random(8)),
                'title' => $faker->sentence(3),
                'voucher_type' => $faker->randomElement(['discount', 'freeship']),
                'value' => $faker->randomFloat(2, 5, 100),
                'discount_type' => $faker->randomElement(['amount', 'percent']),
                'min_order_value' => $faker->randomFloat(2, 50, 200),
                'max_discount_value' => $faker->randomFloat(2, 10, 50),
                'start_date' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                'end_date' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
                'limit' => $faker->numberBetween(1, 100),
                'is_active' => $faker->boolean(80),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
