<?php

namespace Database\Factories;

use App\Models\Production\WorkCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkCenterFactory extends Factory
{
    protected $model = WorkCenter::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('??-####'),
            'name' => fake()->name(),
            'type' => fake()->randomElement(['machine', 'workstation', 'assembly_line', 'manual']),
            'description' => fake()->sentence(),
            'cost_per_hour' => fake()->randomFloat(2, 0, 1000),
            'efficiency_rate' => fake()->randomFloat(2, 0, 1000),
            'is_active' => fake()->boolean(),
        ];
    }

    public function type_machine(): static
    {
        return $this->state(['type' => 'machine']);
    }

    public function type_workstation(): static
    {
        return $this->state(['type' => 'workstation']);
    }

    public function type_assembly_line(): static
    {
        return $this->state(['type' => 'assembly_line']);
    }

    public function type_manual(): static
    {
        return $this->state(['type' => 'manual']);
    }

}
