<?php

namespace Database\Factories;

use App\Models\Workflow\TriggerWorkflowMapping;
use Illuminate\Database\Eloquent\Factories\Factory;

class TriggerWorkflowMappingFactory extends Factory
{
    protected $model = TriggerWorkflowMapping::class;

    public function definition(): array
    {
        return [
            'trigger_id' => Trigger::factory(),
            'workflow_id' => WorkflowDefinition::factory(),
            'priority' => fake()->numberBetween(0, 100),
            'conditions' => json_encode([]),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }

    public function status_active(): static
    {
        return $this->state(['status' => 'active']);
    }

    public function status_inactive(): static
    {
        return $this->state(['status' => 'inactive']);
    }

}
