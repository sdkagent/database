<?php

namespace Database\Factories;

use App\Models\Workflow\WorkflowNode;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowNodeFactory extends Factory
{
    protected $model = WorkflowNode::class;

    public function definition(): array
    {
        return [
            'workflow_id' => WorkflowDefinition::factory(),
            'type' => fake()->randomElement(['trigger', 'action', 'condition', 'approval', 'wait', 'gateway', 'end']),
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'config' => json_encode([]),
            'position_x' => fake()->numberBetween(0, 100),
            'position_y' => fake()->numberBetween(0, 100),
            'timeout_seconds' => fake()->numberBetween(60, 86400),
            'retry_count' => fake()->numberBetween(0, 100),
            'retry_delay' => fake()->numberBetween(0, 100),
        ];
    }

    public function type_trigger(): static
    {
        return $this->state(['type' => 'trigger']);
    }

    public function type_action(): static
    {
        return $this->state(['type' => 'action']);
    }

    public function type_condition(): static
    {
        return $this->state(['type' => 'condition']);
    }

    public function type_approval(): static
    {
        return $this->state(['type' => 'approval']);
    }

    public function type_wait(): static
    {
        return $this->state(['type' => 'wait']);
    }

    public function type_gateway(): static
    {
        return $this->state(['type' => 'gateway']);
    }

    public function type_end(): static
    {
        return $this->state(['type' => 'end']);
    }

}
