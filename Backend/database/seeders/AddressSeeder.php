<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AddressSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $userIds = DB::table('users')->pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->error("Chưa có dữ liệu users!");
            return;
        }

        foreach ($userIds as $userId) {
            DB::table('addresses')->insert([
                'user_id' => $userId,
                'fullname' => $faker->name,
                'phone' => $faker->e164PhoneNumber, // hoặc phoneNumber cũng được
                'address' => $faker->city,
                'specific_address' => $faker->streetAddress,
                'note' => $faker->sentence,
                'is_default' => $faker->boolean(30), // 30% là true
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
