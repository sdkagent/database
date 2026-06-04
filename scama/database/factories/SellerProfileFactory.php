<?php

namespace Database\Factories;

use App\Models\Seller\SellerProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerProfileFactory extends Factory
{
    protected $model = SellerProfile::class;

    public function definition(): array
    {
        return [
            'user_id'            => \App\Models\User::factory(),
            'store_name'         => fake()->optional()->company(),
            'store_description'  => fake()->optional()->paragraph(),
            'store_logo_url'     => fake()->optional()->imageUrl(),
            'store_cover_url'    => fake()->optional()->imageUrl(),
            'status'             => fake()->randomElement(['pending', 'active', 'suspended', 'banned']),
            'current_balance'    => fake()->randomFloat(4, 0, 10000),
            'default_commission' => fake()->randomFloat(2, 50, 100),
            'verified_at'        => fake()->optional(0.5)->dateTimeThisYear(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'pending',
        ]);
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

    public function banned(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'banned',
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn(array $attrs) => [
            'verified_at' => now(),
        ]);
    }
}
