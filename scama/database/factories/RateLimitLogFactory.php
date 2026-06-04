<?php

namespace Database\Factories;

use App\Models\Llm\RateLimitLog;
use App\Models\Llm\RateLimitRule;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RateLimitLogFactory extends Factory
{
    protected $model = RateLimitLog::class;

    public function definition(): array
    {
        return [
            'rule_id'     => RateLimitRule::factory(),
            'user_id'     => User::factory(),
            'ip_address'  => fake()->ipv4(),
            'route'       => fake()->randomElement(['api/v1/verify', 'api/v1/activate', 'api/v1/products']),
            'http_method' => fake()->randomElement(['GET', 'POST']),
            'identifier'  => fake()->uuid(),
        ];
    }

    public function http_method_get(): static
    {
        return $this->state(['http_method' => 'GET']);
    }

    public function http_method_post(): static
    {
        return $this->state(['http_method' => 'POST']);
    }
}
