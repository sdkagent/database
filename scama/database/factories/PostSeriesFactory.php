<?php

namespace Database\Factories;

use App\Models\Content\PostSeries;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostSeriesFactory extends Factory
{
    protected $model = PostSeries::class;

    public function definition(): array
    {
        $title = fake()->sentence(4);
        return [
            'title'       => $title,
            'slug'        => Str::slug($title),
            'description' => fake()->paragraph(),
            'cover_image' => fake()->optional()->imageUrl(),
            'author_id'   => \App\Models\User::factory(),
            'status'      => fake()->randomElement(['active', 'archived']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'active']);
    }

    public function archived(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'archived']);
    }
}
