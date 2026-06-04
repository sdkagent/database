<?php

namespace Database\Factories;

use App\Models\Cms\VideoGallery;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VideoGalleryFactory extends Factory
{
    protected $model = VideoGallery::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [
            'title'       => $title,
            'slug'        => Str::slug($title),
            'description' => fake()->paragraph(),
            'thumbnail'   => fake()->optional()->imageUrl(),
            'author_id'   => \App\Models\User::factory(),
            'status'      => fake()->randomElement(['active', 'archived']),
            'sort_order'  => fake()->numberBetween(0, 100),
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
