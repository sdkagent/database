<?php

namespace Database\Factories;

use App\Models\Theme\ThemeSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeSettingFactory extends Factory
{
    protected $model = ThemeSetting::class;

    public function definition(): array
    {
        return [
            'theme_id' => \App\Models\Theme::factory(),
            'key'      => fake()->randomElement(['primary_color', 'secondary_color', 'font_family', 'layout', 'border_radius']),
            'value'    => fake()->sentence(),
        ];
    }
}
