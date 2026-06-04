<?php

namespace Database\Factories;

use App\Models\Content\ContentBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContentBlockFactory extends Factory
{
    protected $model = ContentBlock::class;

    public function definition(): array
    {
        return [
            'key'       => fake()->unique()->word() . '_block',
            'title'     => fake()->sentence(),
            'content'   => fake()->paragraphs(3, true),
            'type'      => fake()->randomElement(['html', 'text', 'json']),
            'locations' => ['header', 'sidebar'],
            'active'    => true,
        ];
    }

    public function type_html(): static
    {
        return $this->state(['type' => 'html']);
    }

    public function type_json(): static
    {
        return $this->state(['type' => 'json']);
    }

    public function type_text(): static
    {
        return $this->state(['type' => 'text']);
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['active' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => ['active' => false]);
    }
}
