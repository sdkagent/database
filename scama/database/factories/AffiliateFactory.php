<?php

namespace Database\Factories;

use App\Models\Commerce\Affiliate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AffiliateFactory extends Factory
{
    protected $model = Affiliate::class;

    public function definition(): array
    {
        return [
            'user_id'         => \App\Models\User::factory(),
            'code'            => strtoupper(Str::random(8)),
            'commission_rate' => fake()->randomFloat(2, 5, 50),
            'total_earned'    => fake()->randomFloat(2, 0, 10000),
            'total_paid'      => fake()->randomFloat(2, 0, 5000),
            'status'          => fake()->randomElement(['active', 'suspended']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'active',
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'suspended',
        ]);
    }
}
