<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'vu723172@gmail.com';

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'google_id' => 'admin',
            ]);
        }

        // Gán role nếu chưa có
        if (!$user->hasAnyRole(['admin', 'staff'])) {
            $user->assignRole('admin', 'staff');
        }
    }
}
