<?php

namespace Database\Factories;

use App\Models\Logging\MaintenanceWindow;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceWindowFactory extends Factory
{
    protected $model = MaintenanceWindow::class;

    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status'      => fake()->randomElement(['scheduled', 'in_progress', 'completed', 'cancelled']),
            'starts_at'   => fake()->dateTimeBetween('now', '+1 week'),
            'ends_at'     => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'created_by'  => User::factory(),
        ];
    }

    public function scheduled(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'scheduled']);
    }

    public function inProgress(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'in_progress']);
    }

    public function completed(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'completed']);
    }

    public function cancelled(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'cancelled']);
    }
}
