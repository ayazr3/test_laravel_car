<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء سجل واحد فقط (لأن الإعدادات عادةً تكون مفردة)
        Setting::factory()->create();
    }
}
