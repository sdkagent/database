<?php

namespace Database\Factories;

use App\Models\Theme\ThemeGenerationLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeGenerationLogFactory extends Factory
{
    protected $model = ThemeGenerationLog::class;

    public function definition(): array
    {
        return [
            'generation_id' => \App\Models\ThemeGeneration::factory(),
            'message'       => fake()->sentence(),
        ];
    }
}
