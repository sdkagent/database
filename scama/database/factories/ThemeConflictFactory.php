<?php

namespace Database\Factories;

use App\Models\Theme\ThemeConflict;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeConflictFactory extends Factory
{
    protected $model = ThemeConflict::class;

    public function definition(): array
    {
        return [
            'theme_id'           => \App\Models\Theme::factory(),
            'conflicting_plugin' => fake()->randomElement(['Plugin A', 'Plugin B', 'Plugin C', 'Plugin D']),
            'description'        => fake()->paragraph(),
        ];
    }
}
