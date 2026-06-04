<?php

namespace Database\Factories;

use App\Models\Content\PostView;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostViewFactory extends Factory
{
    protected $model = PostView::class;

    public function definition(): array
    {
        return [
            'post_id'    => \App\Models\Post::factory(),
            'user_id'    => \App\Models\User::factory(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}
