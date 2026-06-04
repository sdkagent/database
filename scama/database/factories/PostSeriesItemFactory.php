<?php

namespace Database\Factories;

use App\Models\Content\PostSeriesItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostSeriesItemFactory extends Factory
{
    protected $model = PostSeriesItem::class;

    public function definition(): array
    {
        return [
            'series_id'  => \App\Models\PostSeries::factory(),
            'post_id'    => \App\Models\Post::factory(),
            'part_order' => fake()->numberBetween(1, 20),
            'part_title' => fake()->optional()->sentence(),
        ];
    }
}
