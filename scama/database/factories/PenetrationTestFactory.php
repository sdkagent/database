<?php

namespace Database\Factories;

use App\Models\Security\PenetrationTest;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenetrationTestFactory extends Factory
{
    protected $model = PenetrationTest::class;

    public function definition(): array
    {
        return [
            'test_type' => fake()->randomElement(['external', 'internal', 'web_app', 'mobile_app']),
            'target'    => fake()->url(),
            'status'    => fake()->randomElement(['pending', 'in_progress', 'completed', 'failed']),
            'findings'  => ['critical' => fake()->numberBetween(0, 5), 'high' => fake()->numberBetween(0, 10)],
        ];
    }

    public function external(): static
    {
        return $this->state(['test_type' => 'external']);
    }

    public function internal(): static
    {
        return $this->state(['test_type' => 'internal']);
    }

    public function webApp(): static
    {
        return $this->state(['test_type' => 'web_app']);
    }

    public function mobileApp(): static
    {
        return $this->state(['test_type' => 'mobile_app']);
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function inProgress(): static
    {
        return $this->state(['status' => 'in_progress']);
    }

    public function completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function failed(): static
    {
        return $this->state(['status' => 'failed']);
    }
}
