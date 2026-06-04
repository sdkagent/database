<?php

namespace Database\Factories;

use App\Models\Workflow\ApprovalAssignee;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovalAssigneeFactory extends Factory
{
    protected $model = ApprovalAssignee::class;

    public function definition(): array
    {
        return [
            'stage_id' => ApprovalStage::factory(),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'response' => fake()->sentence(),
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

}
