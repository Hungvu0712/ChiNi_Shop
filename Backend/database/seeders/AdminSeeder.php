<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'vu723172@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('admin123'),
            'google_id' => 'admin',
        ]);

        $user->assignRole('admin', 'staff');
    }
}
