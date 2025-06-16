<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        // Tạo admin user mặc định
        User::firstOrCreate(
            ['email' => 'admin@hangthethao.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@hangthethao.com',
                'password' => Hash::make('1'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Tạo user test mặc định
        User::firstOrCreate(
            ['email' => 'user@hangthethao.com'],
            [
                'name' => 'Test User',
                'email' => 'user@hangthethao.com',
                'password' => Hash::make('1'),
                'role' => 'user',
                'phone' => '0123456789',
                'address' => 'Hà Nội, Việt Nam',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        echo "Admin user created: admin@hangthethao.com / 1\n";
        echo "Test user created: user@hangthethao.com / 1\n";
    }
}
