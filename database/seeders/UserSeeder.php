<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        // إنشاء مستخدم مدير (admin)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456789'),
            'phone' => '+966501234567',
            'role' => 'admin',
            'location' => json_encode([
                'lat' => 24.7136,
                'lng' => 46.6753,
                'address' => 'Riyadh, Saudi Arabia'
            ]),
        ]);

        // إنشاء مستخدم بائع (vendor)
        User::create([
            'name' => 'Vendor User',
            'email' => 'vendor@example.com',
            'password' => Hash::make('123456789'),
            'phone' => '+966502345678',
            'role' => 'vendor',
            'location' => json_encode([
                'lat' => 24.7136,
                'lng' => 46.6753,
                'address' => 'Riyadh, Saudi Arabia'
            ]),
        ]);

        // إنشاء مستخدمين عشوائيين باستخدام Factory
        \App\Models\User::factory(10)->create();
    }
}

