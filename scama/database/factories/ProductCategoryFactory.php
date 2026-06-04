<?php

namespace Database\Factories;

use App\Models\Product\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        return [
            'parent_id'   => null,
            'name'        => fake()->unique()->randomElement(['Electronics', 'Clothing', 'Books', 'Home & Garden', 'Sports']),
            'slug'        => Str::slug(fake()->unique()->word()),
            'description' => fake()->optional()->sentence(),
            'image_url'   => fake()->optional()->imageUrl(),
            'sort_order'  => fake()->numberBetween(0, 100),
        ];
    }

    public function child(): static
    {
        return $this->state(fn(array $attrs) => [
            'parent_id' => \App\Models\ProductCategory::factory(),
        ]);
    }
}
