<?php

namespace Database\Factories;

use App\Models\Llm\RateLimitRule;
use Illuminate\Database\Eloquent\Factories\Factory;

class RateLimitRuleFactory extends Factory
{
    protected $model = RateLimitRule::class;

    public function definition(): array
    {
        return [
            'name'             => fake()->randomElement(['API Rate Limit', 'Auth Rate Limit', 'Search Rate Limit']),
            'route_pattern'    => fake()->randomElement(['api/*', 'api/v1/*', 'api/auth/*']),
            'http_method'      => fake()->randomElement(['GET', 'POST', 'PUT', 'DELETE', null]),
            'max_requests'     => fake()->numberBetween(30, 1000),
            'window_seconds'   => fake()->numberBetween(60, 3600),
            'response_code'    => 429,
            'response_message' => 'Too many requests',
            'is_active'        => fake()->boolean(80),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['is_active' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => ['is_active' => false]);
    }
}
