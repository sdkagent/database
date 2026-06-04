<?php

namespace Database\Factories;

use App\Models\Workflow\ApprovalRequest;
use App\Models\Auth\User;
use App\Models\Workflow\WorkflowNode;
use App\Models\Workflow\WorkflowRun;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApprovalRequestFactory extends Factory
{
    protected $model = ApprovalRequest::class;

    public function definition(): array
    {
        return [
            'workflow_run_id' => WorkflowRun::factory(),
            'node_id' => WorkflowNode::factory(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'cancelled']),
            'requested_by' => User::factory(),
            'requested_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'responded_at' => fake()->optional(0.5)->dateTimeBetween('-1 day', '+1 week'),
            'notes' => fake()->sentence(),
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

    public function status_cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

}
