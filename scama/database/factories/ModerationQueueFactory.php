<?php

namespace Database\Factories;

use App\Models\Moderation\ModerationQueue;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModerationQueueFactory extends Factory
{
    protected $model = ModerationQueue::class;

    public function definition(): array
    {
        return [
            'content_type' => fake()->randomElement(['review', 'product', 'comment', 'profile', 'listing']),
            'content_id'   => fake()->numberBetween(1, 1000),
            'reported_by'  => User::factory(),
            'status'       => fake()->randomElement(['pending', 'reviewed', 'approved', 'rejected', 'escalated']),
            'priority'     => fake()->randomElement(['low', 'normal', 'high', 'critical']),
            'assigned_to'  => null,
            'notes'        => fake()->optional()->sentence(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'pending']);
    }

    public function approved(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'approved']);
    }

    public function rejected(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'rejected']);
    }

    public function critical(): static
    {
        return $this->state(fn(array $attrs) => ['priority' => 'critical']);
    }

    public function content_type_review(): static
    {
        return $this->state(['content_type' => 'review']);
    }

    public function content_type_product(): static
    {
        return $this->state(['content_type' => 'product']);
    }

    public function content_type_comment(): static
    {
        return $this->state(['content_type' => 'comment']);
    }

    public function content_type_profile(): static
    {
        return $this->state(['content_type' => 'profile']);
    }

    public function content_type_listing(): static
    {
        return $this->state(['content_type' => 'listing']);
    }

    public function status_reviewed(): static
    {
        return $this->state(['status' => 'reviewed']);
    }

    public function status_escalated(): static
    {
        return $this->state(['status' => 'escalated']);
    }

    public function priority_low(): static
    {
        return $this->state(['priority' => 'low']);
    }

    public function priority_normal(): static
    {
        return $this->state(['priority' => 'normal']);
    }

    public function priority_high(): static
    {
        return $this->state(['priority' => 'high']);
    }
}
