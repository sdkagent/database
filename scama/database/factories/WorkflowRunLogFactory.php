<?php

namespace Database\Factories;

use App\Models\Workflow\WorkflowRunLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Workflow\WorkflowNode;
use App\Models\Workflow\WorkflowRun;


class WorkflowRunLogFactory extends Factory
{
    protected $model = WorkflowRunLog::class;

    public function definition(): array
    {
        return [
            'run_id' => WorkflowRun::factory(),
            'node_id' => WorkflowNode::factory(),
            'action_type' => fake()->randomElement(['approve', 'reject', 'skip', 'notify']),
            'level' => fake()->randomElement(['info', 'warn', 'error', 'debug']),
            'message' => fake()->sentence(),
            'payload' => json_encode([]),
        ];
    }

    public function level_info(): static
    {
        return $this->state(['level' => 'info']);
    }

    public function level_warn(): static
    {
        return $this->state(['level' => 'warn']);
    }

    public function level_error(): static
    {
        return $this->state(['level' => 'error']);
    }

    public function level_debug(): static
    {
        return $this->state(['level' => 'debug']);
    }

}
