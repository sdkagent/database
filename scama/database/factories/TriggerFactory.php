<?php

namespace Database\Factories;

use App\Models\Workflow\Trigger;
use Illuminate\Database\Eloquent\Factories\Factory;

class TriggerFactory extends Factory
{
    protected $model = Trigger::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'slug' => fake()->unique()->slug(),
            'event_type' => fake()->randomElement(['order.created', 'payment.received', 'user.registered', 'ticket.opened']),
            'description' => fake()->sentence(),
            'config' => json_encode([]),
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
