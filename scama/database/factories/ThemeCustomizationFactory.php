<?php

namespace Database\Factories;

use App\Models\Theme\ThemeCustomization;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeCustomizationFactory extends Factory
{
    protected $model = ThemeCustomization::class;

    public function definition(): array
    {
        return [
            'theme_id'   => \App\Models\Theme::factory(),
            'user_id'    => \App\Models\User::factory(),
            'custom_css' => fake()->optional()->css(),
            'custom_js'  => fake()->optional()->sentence(),
        ];
    }
}
