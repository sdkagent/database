<?php

namespace Database\Factories;

use App\Models\Workflow\WorkflowRunVariable;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Workflow\WorkflowRun;


class WorkflowRunVariableFactory extends Factory
{
    protected $model = WorkflowRunVariable::class;

    public function definition(): array
    {
        return [
            'run_id' => WorkflowRun::factory(),
            'name' => fake()->name(),
            'value' => json_encode([]),
        ];
    }

}
