<?php

namespace Database\Factories;

use App\Models\Support\UserGuide;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserGuideFactory extends Factory
{
    protected $model = UserGuide::class;

    public function definition(): array
    {
        $title = fake()->sentence();
        return [
            'author_id' => \App\Models\User::factory(),
            'title'     => $title,
            'slug'      => Str::slug($title),
            'content'   => fake()->paragraphs(5, true),
            'status'    => fake()->randomElement(['published', 'draft']),
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
