<?php

namespace Database\Factories;

use App\Models\Content\PostComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentFactory extends Factory
{
    protected $model = PostComment::class;

    public function definition(): array
    {
        return [
            'post_id'     => \App\Models\Post::factory(),
            'user_id'     => \App\Models\User::factory(),
            'parent_id'   => null,
            'author_name' => fake()->name(),
            'author_email' => fake()->email(),
            'body'        => fake()->paragraph(),
            'status'      => fake()->randomElement(['pending', 'approved', 'spam']),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'approved']);
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'pending']);
    }

    public function spam(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'spam']);
    }
}
