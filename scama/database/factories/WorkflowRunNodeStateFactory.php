<?php

namespace Database\Factories;

use App\Models\Workflow\WorkflowRunNodeState;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowRunNodeStateFactory extends Factory
{
    protected $model = WorkflowRunNodeState::class;

    public function definition(): array
    {
        return [
            'run_id' => WorkflowRun::factory(),
            'node_id' => WorkflowNode::factory(),
            'status' => fake()->randomElement(['pending', 'running', 'completed', 'failed', 'skipped', 'retrying']),
            'input' => json_encode([]),
            'output' => json_encode([]),
            'attempts' => fake()->numberBetween(1, 5),
        ];
    }

    public function status_pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function status_running(): static
    {
        return $this->state(['status' => 'running']);
    }

    public function status_completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function status_failed(): static
    {
        return $this->state(['status' => 'failed']);
    }

    public function status_skipped(): static
    {
        return $this->state(['status' => 'skipped']);
    }

    public function status_retrying(): static
    {
        return $this->state(['status' => 'retrying']);
    }

}
