<?php

namespace Database\Factories;

use App\Models\Cms\CmsBanner;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsBannerFactory extends Factory
{
    protected $model = CmsBanner::class;

    public function definition(): array
    {
        return [
            'title'    => fake()->sentence(),
            'image'    => fake()->imageUrl(1200, 400),
            'link'     => fake()->url(),
            'status'   => fake()->randomElement(['published', 'draft']),
            'position' => fake()->numberBetween(0, 100),
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
