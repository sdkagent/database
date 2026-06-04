<?php

namespace Database\Factories;

use App\Models\Security\HealthCheck;
use Illuminate\Database\Eloquent\Factories\Factory;

class HealthCheckFactory extends Factory
{
    protected $model = HealthCheck::class;

    public function definition(): array
    {
        return [
            'check_type'       => fake()->randomElement(['database', 'cache', 'queue', 'storage', 'api', 'mail', 'search']),
            'status'           => fake()->randomElement(['pass', 'warn', 'fail']),
            'response_time_ms' => fake()->numberBetween(5, 5000),
            'message'          => fake()->optional()->sentence(),
        ];
    }

    public function pass(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'pass']);
    }

    public function warn(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'warn']);
    }

    public function fail(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'fail']);
    }

    public function check_type_database(): static
    {
        return $this->state(['check_type' => 'database']);
    }

    public function check_type_cache(): static
    {
        return $this->state(['check_type' => 'cache']);
    }

    public function check_type_queue(): static
    {
        return $this->state(['check_type' => 'queue']);
    }

    public function check_type_storage(): static
    {
        return $this->state(['check_type' => 'storage']);
    }

    public function check_type_api(): static
    {
        return $this->state(['check_type' => 'api']);
    }

    public function check_type_mail(): static
    {
        return $this->state(['check_type' => 'mail']);
    }

    public function check_type_search(): static
    {
        return $this->state(['check_type' => 'search']);
    }
}
