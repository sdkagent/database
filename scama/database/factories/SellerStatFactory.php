<?php

namespace Database\Factories;

use App\Models\Seller\SellerStat;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerStatFactory extends Factory
{
    protected $model = SellerStat::class;

    public function definition(): array
    {
        return [
            'seller_id'      => \App\Models\SellerProfile::factory(),
            'period_type'    => fake()->randomElement(['daily', 'weekly', 'monthly']),
            'period_date'    => fake()->date(),
            'total_sales'    => fake()->randomFloat(2, 0, 50000),
            'total_earnings' => fake()->randomFloat(2, 0, 10000),
            'total_orders'   => fake()->numberBetween(0, 500),
            'total_products' => fake()->numberBetween(0, 50),
            'avg_rating'     => fake()->optional()->randomFloat(2, 1, 5),
            'review_count'   => fake()->numberBetween(0, 200),
        ];
    }

    public function daily(): static
    {
        return $this->state(fn(array $attrs) => [
            'period_type' => 'daily',
        ]);
    }

    public function weekly(): static
    {
        return $this->state(fn(array $attrs) => [
            'period_type' => 'weekly',
        ]);
    }

    public function monthly(): static
    {
        return $this->state(fn(array $attrs) => [
            'period_type' => 'monthly',
        ]);
    }
}
