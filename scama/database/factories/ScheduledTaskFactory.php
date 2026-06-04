<?php

namespace Database\Factories;

use App\Models\System\ScheduledTask;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduledTaskFactory extends Factory
{
    protected $model = ScheduledTask::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'cron_expression' => fake()->randomElement(['0 0 * * *', '*/5 * * * *', '0 */6 * * *']),
            'task_type' => fake()->randomElement(['run_workflow', 'call_webhook', 'send_report', 'run_sql', 'custom']),
            'config' => json_encode([]),
            'status' => fake()->randomElement(['active', 'paused', 'completed', 'failed']),
            'is_system' => fake()->boolean(),
            'created_by' => User::factory(),
        ];
    }

    public function task_type_run_workflow(): static
    {
        return $this->state(['task_type' => 'run_workflow']);
    }

    public function task_type_call_webhook(): static
    {
        return $this->state(['task_type' => 'call_webhook']);
    }

    public function task_type_send_report(): static
    {
        return $this->state(['task_type' => 'send_report']);
    }

    public function task_type_run_sql(): static
    {
        return $this->state(['task_type' => 'run_sql']);
    }

    public function task_type_custom(): static
    {
        return $this->state(['task_type' => 'custom']);
    }

    public function status_active(): static
    {
        return $this->state(['status' => 'active']);
    }

    public function status_paused(): static
    {
        return $this->state(['status' => 'paused']);
    }

    public function status_completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function status_failed(): static
    {
        return $this->state(['status' => 'failed']);
    }

}
