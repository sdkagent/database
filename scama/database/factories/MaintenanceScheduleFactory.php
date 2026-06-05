<?php

namespace Database\Factories;

use App\Models\Logging\MaintenanceSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Production\WorkCenter;


class MaintenanceScheduleFactory extends Factory
{
    protected $model = MaintenanceSchedule::class;

    public function definition(): array
    {
        return [
            'work_center_id' => WorkCenter::factory(),
            'title' => fake()->sentence(),
            'type' => fake()->randomElement(['preventive', 'predictive', 'corrective', 'emergency']),
            'frequency' => fake()->randomElement(['daily', 'weekly', 'monthly', 'quarterly', 'yearly', 'hours']),
            'frequency_value' => fake()->randomFloat(2, 0, 1000),
            'estimated_hours' => fake()->randomFloat(2, 0, 1000),
            'is_active' => fake()->boolean(),
        ];
    }

    public function type_preventive(): static
    {
        return $this->state(['type' => 'preventive']);
    }

    public function type_predictive(): static
    {
        return $this->state(['type' => 'predictive']);
    }

    public function type_corrective(): static
    {
        return $this->state(['type' => 'corrective']);
    }

    public function type_emergency(): static
    {
        return $this->state(['type' => 'emergency']);
    }

    public function frequency_daily(): static
    {
        return $this->state(['frequency' => 'daily']);
    }

    public function frequency_weekly(): static
    {
        return $this->state(['frequency' => 'weekly']);
    }

    public function frequency_monthly(): static
    {
        return $this->state(['frequency' => 'monthly']);
    }

    public function frequency_quarterly(): static
    {
        return $this->state(['frequency' => 'quarterly']);
    }

    public function frequency_yearly(): static
    {
        return $this->state(['frequency' => 'yearly']);
    }

    public function frequency_hours(): static
    {
        return $this->state(['frequency' => 'hours']);
    }

}
