<?php

namespace Database\Factories;

use App\Models\Cms\SlugHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlugHistoryFactory extends Factory
{
    protected $model = SlugHistory::class;

    public function definition(): array
    {
        return [
            'content_type' => fake()->randomElement(['post', 'cms_page', 'product', 'knowledge_base', 'faq_item']),
            'content_id'   => fake()->numberBetween(1, 1000),
            'old_slug'     => fake()->slug(),
            'new_slug'     => fake()->slug(),
        ];
    }

    public function content_type_post(): static
    {
        return $this->state(['content_type' => 'post']);
    }

    public function content_type_cms_page(): static
    {
        return $this->state(['content_type' => 'cms_page']);
    }

    public function content_type_product(): static
    {
        return $this->state(['content_type' => 'product']);
    }

    public function content_type_knowledge_base(): static
    {
        return $this->state(['content_type' => 'knowledge_base']);
    }

    public function content_type_faq_item(): static
    {
        return $this->state(['content_type' => 'faq_item']);
    }
}
