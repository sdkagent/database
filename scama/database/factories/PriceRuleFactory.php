<?php

namespace Database\Factories;

use App\Models\Pricing\PriceRule;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceRuleFactory extends Factory
{
    protected $model = PriceRule::class;

    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->randomElement(['Summer Sale', 'Flash Deal', 'Bulk Discount', 'Loyalty Price']),
            'slug'        => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'priority'    => fake()->numberBetween(0, 100),
            'conditions'  => ['min_cart_total' => fake()->randomFloat(2, 10, 500)],
            'adjustments' => ['discount_percent' => fake()->randomFloat(2, 5, 50)],
            'applies_to'  => fake()->randomElement(['all', 'products', 'categories', 'users', 'user_roles']),
            'stackable'   => fake()->boolean(30),
            'status'      => fake()->randomElement(['active', 'inactive', 'expired']),
            'starts_at'   => fake()->dateTimeThisMonth(),
            'expires_at'  => fake()->dateTimeBetween('+1 month', '+1 year'),
            'created_by'  => User::factory(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'active']);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'inactive']);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'expired']);
    }

    public function applies_to_all(): static
    {
        return $this->state(['applies_to' => 'all']);
    }

    public function applies_to_products(): static
    {
        return $this->state(['applies_to' => 'products']);
    }

    public function applies_to_categories(): static
    {
        return $this->state(['applies_to' => 'categories']);
    }

    public function applies_to_users(): static
    {
        return $this->state(['applies_to' => 'users']);
    }

    public function applies_to_user_roles(): static
    {
        return $this->state(['applies_to' => 'user_roles']);
    }
}
