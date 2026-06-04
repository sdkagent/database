<?php

namespace Database\Factories;

use App\Models\Product\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubscriptionPlanFactory extends Factory
{
    protected $model = SubscriptionPlan::class;

    public function definition(): array
    {
        return [
            'name'            => fake()->randomElement(['Basic', 'Pro', 'Enterprise', 'Starter']),
            'code'            => fake()->optional()->unique()->word(),
            'duration_months' => fake()->randomElement([1, 3, 6, 12]),
            'max_activations' => fake()->numberBetween(1, 10),
            'price_monthly'   => fake()->randomFloat(2, 5, 100),
            'price_yearly'    => fake()->randomFloat(2, 50, 1000),
            'features'        => fake()->randomElements(['api_access', 'priority_support', 'unlimited_activations'], rand(1, 3)),
            'status'          => fake()->boolean(80),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => false,
        ]);
    }
}
