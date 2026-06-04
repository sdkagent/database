<?php

namespace Database\Factories;

use App\Models\Product\ProductDiscount;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductDiscountFactory extends Factory
{
    protected $model = ProductDiscount::class;

    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'name'       => fake()->unique()->randomElement(['Seasonal Discount', 'Clearance Sale', 'Bundle Deal', 'Early Bird']),
            'type'       => fake()->randomElement(['percentage', 'fixed']),
            'value'      => fake()->randomFloat(2, 5, 100),
            'max_uses'   => fake()->optional()->numberBetween(10, 1000),
            'used_count' => fake()->numberBetween(0, 50),
            'starts_at'  => fake()->dateTimeThisMonth(),
            'ends_at'    => fake()->optional()->dateTimeBetween('+1 month', '+6 months'),
        ];
    }

    public function percentage(): static
    {
        return $this->state(fn(array $attrs) => [
            'type'  => 'percentage',
            'value' => fake()->randomFloat(2, 5, 50),
        ]);
    }

    public function fixed(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'fixed',
        ]);
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'starts_at' => now()->subDay(),
            'ends_at'   => now()->addMonth(),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attrs) => [
            'starts_at' => now()->subMonths(2),
            'ends_at'   => now()->subDay(),
        ]);
    }
}
