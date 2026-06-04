<?php

namespace Database\Factories;

use App\Models\Support\ChatSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatSessionFactory extends Factory
{
    protected $model = ChatSession::class;

    public function definition(): array
    {
        return [
            'user_id'      => \App\Models\User::factory(),
            'assigned_to'  => \App\Models\User::factory(),
            'status'       => fake()->randomElement(['open', 'closed']),
        ];
    }

    public function open(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'open',
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'closed',
        ]);
    }
}
