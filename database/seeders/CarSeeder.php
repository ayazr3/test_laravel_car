<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        // الحصول على بعض المستخدمين لربط السيارات بهم
        $users = User::where('role', 'vendor')->take(5)->get();

        if ($users->isEmpty()) {
            $users = User::factory(5)->create(['role' => 'vendor']);
        }

        // إنشاء سيارات عينة
        foreach ($users as $user) {
            Car::factory(3)->create(['user_id' => $user->id]);
        }

        // إنشاء سيارات إضافية عشوائية
        Car::factory(15)->create();
    }
}
