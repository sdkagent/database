<?php

namespace Database\Factories;

use App\Models\Workflow\WorkflowRun;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowRunFactory extends Factory
{
    protected $model = WorkflowRun::class;

    public function definition(): array
    {
        return [
            'workflow_id' => WorkflowDefinition::factory(),
            'triggered_by' => User::factory(),
            'trigger_type' => fake()->randomElement(['manual', 'event', 'schedule', 'webhook']),
            'trigger_payload' => json_encode(['event' => 'test']),
            'status' => fake()->randomElement(['running', 'completed', 'failed', 'cancelled', 'paused']),
            'current_node_id' => WorkflowNode::factory(),
        ];
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

    public function status_cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

    public function status_paused(): static
    {
        return $this->state(['status' => 'paused']);
    }

}
