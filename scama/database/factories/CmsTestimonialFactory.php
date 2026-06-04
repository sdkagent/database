<?php

namespace Database\Factories;

use App\Models\Cms\CmsTestimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsTestimonialFactory extends Factory
{
    protected $model = CmsTestimonial::class;

    public function definition(): array
    {
        return [
            'name'     => fake()->name(),
            'position' => fake()->jobTitle(),
            'company'  => fake()->company(),
            'content'  => fake()->paragraphs(2, true),
            'status'   => fake()->randomElement(['published', 'draft']),
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
