<?php

namespace Database\Factories;

use App\Models\Cms\StructuredData;
use Illuminate\Database\Eloquent\Factories\Factory;

class StructuredDataFactory extends Factory
{
    protected $model = StructuredData::class;

    public function definition(): array
    {
        return [
            'content_type' => fake()->randomElement(['post', 'cms_page', 'product']),
            'content_id'   => fake()->numberBetween(1, 1000),
            'schema_type'  => fake()->randomElement(['Article', 'Product', 'FAQPage', 'LocalBusiness']),
            'json_ld'      => ['@context' => 'https://schema.org', '@type' => 'Article'],
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

    public function schema_type_article(): static
    {
        return $this->state(['schema_type' => 'Article']);
    }

    public function schema_type_product(): static
    {
        return $this->state(['schema_type' => 'Product']);
    }

    public function schema_type_faq_page(): static
    {
        return $this->state(['schema_type' => 'FAQPage']);
    }

    public function schema_type_local_business(): static
    {
        return $this->state(['schema_type' => 'LocalBusiness']);
    }
}
