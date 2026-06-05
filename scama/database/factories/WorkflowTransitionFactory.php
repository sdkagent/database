<?php

namespace Database\Factories;

use App\Models\Workflow\WorkflowTransition;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Workflow\WorkflowDefinition;
use App\Models\Workflow\WorkflowNode;


class WorkflowTransitionFactory extends Factory
{
    protected $model = WorkflowTransition::class;

    public function definition(): array
    {
        return [
            'workflow_id' => WorkflowDefinition::factory(),
            'from_node_id' => WorkflowNode::factory(),
            'to_node_id' => WorkflowNode::factory(),
            'condition_expression' => fake()->randomElement(['status == "active"', 'amount > 100', 'type == "premium"']),
            'label' => fake()->words(2, true),
            'priority' => fake()->numberBetween(0, 100),
        ];
    }

}
