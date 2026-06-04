<?php

namespace Database\Factories;

use App\Models\Pricing\PriceOverride;
use App\Models\Product\Product;
use App\Models\Product\SubscriptionPlan;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceOverrideFactory extends Factory
{
    protected $model = PriceOverride::class;

    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'product_id'     => Product::factory(),
            'plan_id'        => null,
            'override_price' => fake()->randomFloat(2, 5, 500),
            'override_type'  => fake()->randomElement(['fixed', 'percentage']),
            'starts_at'      => fake()->dateTimeThisMonth(),
            'expires_at'     => fake()->dateTimeBetween('+1 month', '+1 year'),
            'reason'         => fake()->sentence(),
            'created_by'     => User::factory(),
        ];
    }

    public function fixed(): static
    {
        return $this->state(fn(array $attrs) => ['override_type' => 'fixed']);
    }

    public function percentage(): static
    {
        return $this->state(fn(array $attrs) => ['override_type' => 'percentage']);
    }
}
