<?php

namespace Database\Factories;

use App\Models\Theme\ThemeUpdateLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeUpdateLogFactory extends Factory
{
    protected $model = ThemeUpdateLog::class;

    public function definition(): array
    {
        return [
            'theme_id'     => \App\Models\Theme::factory(),
            'version_from' => fake()->semver(),
            'version_to'   => fake()->semver(),
            'changelog'    => fake()->paragraphs(2, true),
        ];
    }
}
