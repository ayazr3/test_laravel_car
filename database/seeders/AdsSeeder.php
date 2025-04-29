<?php

namespace Database\Seeders;


use App\Models\Ads;
use Illuminate\Database\Seeder;

class AdsSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء 10 إعلانات عشوائية
        Ads::factory(10)->create();

        // إنشاء إعلان مميز (يمكنك إضافة المزيد حسب الحاجة)
        Ads::create([
            'fullname' => 'عرض خاص على السيارات',
            'image' => 'ads/special-offer.jpg',
            'url' => 'https://example.com/special-offer',
            'hit' => 0,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'location' => json_encode([
                'lat' => 24.7136,
                'lng' => 46.6753,
                'address' => 'الرياض، المملكة العربية السعودية'
            ]),
            'email' => 'special@example.com',
            'phone' => '+966501234567',
        ]);
    }
}
