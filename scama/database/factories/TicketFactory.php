<?php

namespace Database\Factories;

use App\Models\Support\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'user_id'     => \App\Models\User::factory(),
            'subject'     => fake()->optional()->sentence(),
            'status'      => fake()->randomElement(['open', 'replied', 'closed']),
            'priority'    => fake()->randomElement(['low', 'medium', 'high']),
            'assigned_to' => \App\Models\User::factory(),
            'category'    => fake()->optional()->word(),
            'closed_at'   => fake()->optional(0.3)->dateTimeThisMonth(),
        ];
    }

    public function open(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'open',
        ]);
    }

    public function replied(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'replied',
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'    => 'closed',
            'closed_at' => now(),
        ]);
    }

    public function low(): static
    {
        return $this->state(fn(array $attrs) => [
            'priority' => 'low',
        ]);
    }

    public function medium(): static
    {
        return $this->state(fn(array $attrs) => [
            'priority' => 'medium',
        ]);
    }

    public function high(): static
    {
        return $this->state(fn(array $attrs) => [
            'priority' => 'high',
        ]);
    }
}
