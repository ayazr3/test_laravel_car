<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'note' => $this->faker->paragraph(3),
            'id_car' => Car::inRandomOrder()->first()->id ?? Car::factory(),
            'is_public' => $this->faker->boolean(70), // 70% احتمالية أن تكون public
        ];
    }

    // حالة خاصة لتقييمات بدون سيارة
    public function withoutCar(): static
    {
        return $this->state(fn (array $attributes) => [
            'id_car' => null,
        ]);
    }

    // حالة خاصة لتقييمات غير public
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_public' => false,
        ]);
    }
}
