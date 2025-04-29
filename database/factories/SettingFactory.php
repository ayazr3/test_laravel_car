<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class SettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'logo' => $this->faker->imageUrl(200, 200, 'logo'), // رابط صورة وهمية للشعار
            'link_instagram' => $this->faker->url(), // رابط إنستجرام وهمي
            'link_facebook' => $this->faker->url(), // رابط فيسبوك وهمي
            'name' => $this->faker->company(), // اسم وهمي (مثل اسم شركة)
            'phone_whatsapp' => $this->faker->phoneNumber(), // رقم واتساب وهمي
            'images' => json_encode([ // صور وهمية (كمصفوفة JSON)
                $this->faker->imageUrl(800, 600, 'car'),
                $this->faker->imageUrl(800, 600, 'interior'),
            ]),
            'sentence' => json_encode([ // جمل وهمية (كمصفوفة JSON)
                $this->faker->sentence(),
                $this->faker->sentence(),
            ]),
        ];
    }
}
