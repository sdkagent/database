<?php

namespace Database\Factories;

use App\Models\Logging\SystemLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class SystemLogFactory extends Factory
{
    protected $model = SystemLog::class;

    public function definition(): array
    {
        return [
            'level'   => fake()->randomElement(['debug', 'info', 'warning', 'error', 'critical']),
            'message' => fake()->sentence(),
            'context' => ['file' => fake()->filePath(), 'line' => fake()->numberBetween(1, 500)],
        ];
    }

    public function error(): static
    {
        return $this->state(fn(array $attrs) => ['level' => 'error']);
    }

    public function critical(): static
    {
        return $this->state(fn(array $attrs) => ['level' => 'critical']);
    }

    public function info(): static
    {
        return $this->state(fn(array $attrs) => ['level' => 'info']);
    }

    public function debug(): static
    {
        return $this->state(fn(array $attrs) => ['level' => 'debug']);
    }

    public function warning(): static
    {
        return $this->state(fn(array $attrs) => ['level' => 'warning']);
    }
}
