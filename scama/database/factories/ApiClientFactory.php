<?php

namespace Database\Factories;

use App\Models\Licensing\ApiClient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ApiClientFactory extends Factory
{
    protected $model = ApiClient::class;

    public function definition(): array
    {
        return [
            'user_id'      => \App\Models\User::factory(),
            'name'         => fake()->optional()->word(),
            'api_key'      => Str::random(32),
            'api_secret'   => Str::random(64),
            'status'       => fake()->randomElement(['active', 'suspended', 'revoked']),
            'rate_limit'   => fake()->numberBetween(30, 120),
            'last_used_at' => fake()->optional()->dateTimeThisMonth(),
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

    public function revoked(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'revoked',
        ]);
    }
}
