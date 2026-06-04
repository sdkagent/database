<?php

namespace Database\Factories;

use App\Models\Cms\Video;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VideoFactory extends Factory
{
    protected $model = Video::class;

    public function definition(): array
    {
        $title = fake()->sentence(3);
        return [
            'gallery_id'  => \App\Models\VideoGallery::factory(),
            'title'       => $title,
            'slug'        => Str::slug($title),
            'description' => fake()->paragraph(),
            'video_url'   => fake()->url(),
            'embed_url'   => 'https://www.youtube.com/embed/' . fake()->uuid(),
            'thumbnail'   => fake()->imageUrl(),
            'duration'    => fake()->numberBetween(30, 3600),
            'file_size'   => fake()->numberBetween(1000000, 500000000),
            'file_type'   => 'video/mp4',
            'author_id'   => \App\Models\User::factory(),
            'status'      => fake()->randomElement(['published', 'draft']),
            'featured'    => fake()->boolean(10),
            'view_count'  => fake()->numberBetween(0, 50000),
            'sort_order'  => fake()->numberBetween(0, 100),
        ];
    }

    public function published(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'published']);
    }

    public function draft(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'draft']);
    }
}
