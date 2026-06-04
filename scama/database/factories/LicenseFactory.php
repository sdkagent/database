<?php

namespace Database\Factories;

use App\Models\Licensing\License;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LicenseFactory extends Factory
{
    protected $model = License::class;

    public function definition(): array
    {
        return [
            'user_id'             => \App\Models\User::factory(),
            'product_id'          => \App\Models\Product::factory(),
            'api_client_id'       => \App\Models\ApiClient::factory(),
            'subscription_id'     => \App\Models\UserSubscription::factory(),
            'license_key'         => strtoupper('LIC-' . Str::random(24)),
            'api_key'             => Str::random(32),
            'status'              => fake()->randomElement(['active', 'suspended', 'expired', 'revoked']),
            'max_activations'     => fake()->numberBetween(1, 10),
            'current_activations' => fake()->numberBetween(0, 5),
            'expires_at'          => fake()->dateTimeBetween('now', '+2 years'),
            'last_activity_at'    => fake()->optional()->dateTimeThisYear(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'     => 'active',
            'expires_at' => now()->addYear(),
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'suspended',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'     => 'expired',
            'expires_at' => now()->subDay(),
        ]);
    }

    public function revoked(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'revoked',
        ]);
    }
}
