<?php

namespace Database\Factories;

use App\Models\Auth\User;
use App\Models\Workflow\WorkflowDefinition;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkflowDefinitionFactory extends Factory
{
    protected $model = WorkflowDefinition::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement(['sales', 'support', 'hr', 'finance', 'inventory']),
            'status' => fake()->randomElement(['draft', 'active', 'paused', 'archived']),
            'version' => fake()->numberBetween(0, 100),
            'config' => json_encode([]),
            'is_system' => fake()->boolean(),
            'created_by' => User::factory(),
        ];
    }

    public function status_draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function status_active(): static
    {
        return $this->state(['status' => 'active']);
    }

    public function status_paused(): static
    {
        return $this->state(['status' => 'paused']);
    }

    public function status_archived(): static
    {
        return $this->state(['status' => 'archived']);
    }

}
