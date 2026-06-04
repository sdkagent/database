<?php

namespace Database\Factories;

use App\Models\Logging\MaintenanceLog;
use App\Models\Logging\MaintenanceSchedule;
use App\Models\Auth\User;
use App\Models\Production\WorkCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceLogFactory extends Factory
{
    protected $model = MaintenanceLog::class;

    public function definition(): array
    {
        return [
            'maintenance_schedule_id' => MaintenanceSchedule::factory(),
            'work_center_id' => WorkCenter::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->sentence(),
            'type' => fake()->randomElement(['preventive', 'predictive', 'corrective', 'emergency']),
            'status' => fake()->randomElement(['planned', 'in_progress', 'completed', 'cancelled']),
            'started_at' => fake()->optional()->dateTimeBetween('-1 week', 'now'),
            'completed_at' => fake()->optional()->dateTimeBetween('now', '+1 week'),
            'duration_hours' => fake()->randomFloat(2, 0, 1000),
            'cost' => fake()->randomFloat(2, 0, 10000),
            'performed_by' => User::factory(),
            'notes' => fake()->sentence(),
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

    public function status_planned(): static
    {
        return $this->state(['status' => 'planned']);
    }

    public function status_in_progress(): static
    {
        return $this->state(['status' => 'in_progress']);
    }

    public function status_completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function status_cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

}
