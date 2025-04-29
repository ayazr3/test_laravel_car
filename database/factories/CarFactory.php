<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    public function definition(): array
    {
        $brands = ['Toyota', 'Honda', 'Ford', 'BMW', 'Mercedes', 'Audi', 'Nissan', 'Hyundai', 'Kia', 'Chevrolet'];
        $colors = ['Red', 'Blue', 'Black', 'White', 'Silver', 'Gray', 'Green', 'Yellow'];
        $currencies = ['SAR', 'USD', 'EUR'];

        return [
            'user_id' => User::factory(),
            'brand' => $this->faker->randomElement($brands),
            'model' => $this->faker->bothify('???-####'),
            'year' => $this->faker->year(2015, 2023),
            'price' => $this->faker->numberBetween(20000, 200000),
            'currency' => $this->faker->randomElement($currencies),
            'images' => json_encode([
                'cars/'.$this->faker->uuid().'.jpg',
                'cars/'.$this->faker->uuid().'.jpg'
            ]),
            'description' => $this->faker->paragraph(3),
            'sold' => $this->faker->boolean(20), // 20% احتمال أن تكون السيارة مباعة
            'color' => $this->faker->randomElement($colors),
            'location' => json_encode([
                'lat' => $this->faker->latitude(24.5, 25.0), // إحداثيات الرياض تقريباً
                'lng' => $this->faker->longitude(46.5, 47.0),
                'address' => $this->faker->address()
            ]),
        ];
    }
}
