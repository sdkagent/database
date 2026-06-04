<?php

namespace Database\Factories;

use App\Models\Commerce\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponFactory extends Factory
{
    protected $model = Coupon::class;

    public function definition(): array
    {
        return [
            'code'             => strtoupper(Str::random(8)),
            'type'             => fake()->randomElement(['percentage', 'fixed_amount']),
            'value'            => fake()->randomFloat(2, 5, 100),
            'min_order_amount' => fake()->randomFloat(2, 0, 50),
            'max_uses'         => fake()->optional()->numberBetween(10, 1000),
            'used_count'       => fake()->numberBetween(0, 50),
            'starts_at'        => fake()->optional()->dateTimeThisMonth(),
            'expires_at'       => fake()->optional()->dateTimeBetween('+1 month', '+1 year'),
            'is_active'        => fake()->boolean(80),
        ];
    }

    public function percentage(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'percentage',
        ]);
    }

    public function fixedAmount(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'fixed_amount',
        ]);
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'is_active'  => true,
            'expires_at' => now()->addYear(),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attrs) => [
            'is_active'  => false,
            'expires_at' => now()->subDay(),
        ]);
    }
}
