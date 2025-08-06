<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo 10 user test (nếu chưa có)
        \App\Models\User::factory()->count(10)->create();

        $this->call([
            RoleSeeder::class,
            AdminSeeder::class,
            MenuSeeder::class,
            PermissionSeeder::class,
            AttributeSeeder::class,
            AttributeItemSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductWithVariantsSeeder::class,
            CartSeeder::class,
            // AddressesTableSeeder::class,
            VoucherSeeder::class,
            // OrdersTableSeeder::class,
            // OrderItemsTableSeeder::class,
            PaymentMethod::class
        ]);
    }
}
