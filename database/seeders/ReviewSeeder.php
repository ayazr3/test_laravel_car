<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء 20 تقييم عشوائي
        Review::factory(20)->create();

        // إنشاء 5 تقييمات بدون سيارة
        Review::factory(5)->withoutCar()->create();

        // إنشاء 3 تقييمات خاصة (غير public)
        Review::factory(3)->private()->create();

        // إنشاء تقييمات لسيارات محددة
        $specialCars = Car::take(3)->get();
        foreach ($specialCars as $car) {
            Review::factory(2)->create([
                'id_car' => $car->id,
                'is_public' => true,
                'note' => 'تقييم ممتاز لهذه السيارة!'
            ]);
        }
    }
}
