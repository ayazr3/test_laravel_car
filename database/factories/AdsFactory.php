<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdsFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, '+3 months');

        return [
            'fullname' => $this->faker->name,
            'image' => 'ads/' . Str::random(10) . '.jpg',
            'url' => $this->faker->url,
            'hit' => $this->faker->numberBetween(0, 10000),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'location' => json_encode([
                'lat' => $this->faker->latitude(),
                'lng' => $this->faker->longitude(),
                'address' => $this->faker->address()
            ]),
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
        ];
    }
}
