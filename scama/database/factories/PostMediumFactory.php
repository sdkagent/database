<?php

namespace Database\Factories;

use App\Models\Content\PostMedium;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostMediumFactory extends Factory
{
    protected $model = PostMedium::class;

    public function definition(): array
    {
        return [
            'post_id'     => \App\Models\Post::factory(),
            'file_name'   => fake()->unique()->word() . '.' . fake()->fileExtension(),
            'file_path'   => 'uploads/' . fake()->uuid() . '.' . fake()->fileExtension(),
            'file_type'   => fake()->mimeType(),
            'file_size'   => fake()->numberBetween(1000, 5000000),
            'is_featured' => fake()->boolean(10),
            'sort_order'  => fake()->numberBetween(0, 100),
        ];
    }
}
