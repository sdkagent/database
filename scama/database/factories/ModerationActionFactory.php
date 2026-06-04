<?php

namespace Database\Factories;

use App\Models\Moderation\ModerationAction;
use App\Models\Moderation\ModerationQueue;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModerationActionFactory extends Factory
{
    protected $model = ModerationAction::class;

    public function definition(): array
    {
        return [
            'queue_item_id' => ModerationQueue::factory(),
            'moderator_id'  => User::factory(),
            'action'        => fake()->randomElement(['approved', 'rejected', 'warned', 'hidden', 'deleted', 'escalated']),
            'reason'        => fake()->sentence(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn(array $attrs) => ['action' => 'approved']);
    }

    public function rejected(): static
    {
        return $this->state(fn(array $attrs) => ['action' => 'rejected']);
    }

    public function deleted(): static
    {
        return $this->state(fn(array $attrs) => ['action' => 'deleted']);
    }

    public function action_warned(): static
    {
        return $this->state(['action' => 'warned']);
    }

    public function action_hidden(): static
    {
        return $this->state(['action' => 'hidden']);
    }

    public function action_escalated(): static
    {
        return $this->state(['action' => 'escalated']);
    }
}
