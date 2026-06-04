<?php

namespace Database\Factories;

use App\Models\Content\PostReaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostReactionFactory extends Factory
{
    protected $model = PostReaction::class;

    public function definition(): array
    {
        return [
            'post_id'  => \App\Models\Post::factory(),
            'user_id'  => \App\Models\User::factory(),
            'reaction' => fake()->randomElement(['like', 'love', 'laugh', 'clap', 'fire']),
        ];
    }

    public function like(): static
    {
        return $this->state(['reaction' => 'like']);
    }

    public function love(): static
    {
        return $this->state(['reaction' => 'love']);
    }

    public function laugh(): static
    {
        return $this->state(['reaction' => 'laugh']);
    }

    public function clap(): static
    {
        return $this->state(['reaction' => 'clap']);
    }

    public function fire(): static
    {
        return $this->state(['reaction' => 'fire']);
    }
}
