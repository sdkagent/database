<?php

namespace Database\Factories;

use App\Models\Commerce\Referral;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReferralFactory extends Factory
{
    protected $model = Referral::class;

    public function definition(): array
    {
        return [
            'affiliate_id' => \App\Models\Affiliate::factory(),
            'referred_id'  => \App\Models\User::factory(),
            'order_id'     => \App\Models\Order::factory(),
            'commission'   => fake()->randomFloat(2, 1, 100),
            'status'       => fake()->randomElement(['pending', 'paid', 'cancelled']),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'pending',
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'paid',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'cancelled',
        ]);
    }
}
