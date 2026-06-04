<?php

namespace Database\Factories;

use App\Models\Product\UserSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSubscriptionFactory extends Factory
{
    protected $model = UserSubscription::class;

    public function definition(): array
    {
        return [
            'user_id'                => \App\Models\User::factory(),
            'plan_id'                => \App\Models\SubscriptionPlan::factory(),
            'product_id'             => \App\Models\Product::factory(),
            'status'                 => fake()->randomElement(['active', 'cancelled', 'expired', 'past_due']),
            'start_date'             => fake()->dateTimeThisYear(),
            'end_date'               => fake()->dateTimeBetween('+1 month', '+1 year'),
            'trial_ends_at'          => fake()->optional(0.3)->dateTimeThisMonth(),
            'stripe_subscription_id' => fake()->optional()->word(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'     => 'active',
            'start_date' => now()->subMonth(),
            'end_date'   => now()->addYear(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'cancelled',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'   => 'expired',
            'end_date' => now()->subDay(),
        ]);
    }

    public function pastDue(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'past_due',
        ]);
    }
}
