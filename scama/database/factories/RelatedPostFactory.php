<?php

namespace Database\Factories;

use App\Models\Content\RelatedPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class RelatedPostFactory extends Factory
{
    protected $model = RelatedPost::class;

    public function definition(): array
    {
        return [
            'post_id'        => \App\Models\Post::factory(),
            'related_post_id' => \App\Models\Post::factory(),
            'relation_type'  => fake()->randomElement(['manual', 'auto_tag', 'auto_category']),
            'weight'         => fake()->numberBetween(0, 100),
        ];
    }

    public function relation_type_manual(): static
    {
        return $this->state(['relation_type' => 'manual']);
    }

    public function relation_type_auto_tag(): static
    {
        return $this->state(['relation_type' => 'auto_tag']);
    }

    public function relation_type_auto_category(): static
    {
        return $this->state(['relation_type' => 'auto_category']);
    }
}
