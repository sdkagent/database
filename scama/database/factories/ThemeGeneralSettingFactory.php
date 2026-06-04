<?php

namespace Database\Factories;

use App\Models\Theme\ThemeGeneralSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeGeneralSettingFactory extends Factory
{
    protected $model = ThemeGeneralSetting::class;

    public function definition(): array
    {
        return [
            'theme_id'      => \App\Models\Theme::factory(),
            'setting_key'   => fake()->randomElement(['bg_color', 'text_color', 'link_color', 'header_style']),
            'setting_value' => fake()->sentence(),
        ];
    }
}
