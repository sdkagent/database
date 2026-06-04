<?php

namespace Database\Factories;

use App\Models\Cms\VideoGuideline;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoGuidelineFactory extends Factory
{
    protected $model = VideoGuideline::class;

    public function definition(): array
    {
        return [
            'video_id'    => \App\Models\Video::factory(),
            'step_order'  => fake()->numberBetween(1, 20),
            'title'       => fake()->sentence(),
            'description' => fake()->paragraph(),
            'time_marker' => fake()->optional()->numberBetween(0, 3600),
            'image'       => fake()->optional()->imageUrl(),
        ];
    }
}
