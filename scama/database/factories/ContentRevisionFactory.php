<?php

namespace Database\Factories;

use App\Models\Content\ContentRevision;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentRevisionFactory extends Factory
{
    protected $model = ContentRevision::class;

    public function definition(): array
    {
        return [
            'content_type' => fake()->randomElement(['post', 'cms_page', 'user_guide', 'knowledge_base']),
            'content_id'   => fake()->numberBetween(1, 1000),
            'title'        => fake()->sentence(),
            'content'      => fake()->paragraphs(3, true),
            'summary'      => fake()->paragraph(),
            'meta'         => ['revision' => fake()->numberBetween(1, 10)],
            'created_by'   => \App\Models\User::factory(),
        ];
    }

    public function content_type_cms_page(): static
    {
        return $this->state(['content_type' => 'cms_page']);
    }

    public function content_type_knowledge_base(): static
    {
        return $this->state(['content_type' => 'knowledge_base']);
    }

    public function content_type_post(): static
    {
        return $this->state(['content_type' => 'post']);
    }

    public function content_type_user_guide(): static
    {
        return $this->state(['content_type' => 'user_guide']);
    }
}
