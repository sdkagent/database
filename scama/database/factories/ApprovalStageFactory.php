<?php

namespace Database\Factories;

use App\Models\Workflow\ApprovalStage;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Workflow\ApprovalRequest;


class ApprovalStageFactory extends Factory
{
    protected $model = ApprovalStage::class;

    public function definition(): array
    {
        return [
            'approval_request_id' => ApprovalRequest::factory(),
            'stage_order' => fake()->numberBetween(0, 100),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'skipped']),
            'strategy' => fake()->randomElement(['any', 'all']),
            'min_approvers' => fake()->numberBetween(0, 100),
        ];
    }

    public function status_pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function status_approved(): static
    {
        return $this->state(['status' => 'approved']);
    }

    public function status_rejected(): static
    {
        return $this->state(['status' => 'rejected']);
    }

    public function status_skipped(): static
    {
        return $this->state(['status' => 'skipped']);
    }

    public function strategy_any(): static
    {
        return $this->state(['strategy' => 'any']);
    }

    public function strategy_all(): static
    {
        return $this->state(['strategy' => 'all']);
    }

}
