<?php

namespace Database\Factories;

use App\Models\Support\ChatMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatMessageFactory extends Factory
{
    protected $model = ChatMessage::class;

    public function definition(): array
    {
        return [
            'session_id' => \App\Models\ChatSession::factory(),
            'user_id'    => \App\Models\User::factory(),
            'message'    => fake()->sentence(),
            'is_agent'   => fake()->boolean(30),
        ];
    }

    public function agent(): static
    {
        return $this->state(fn(array $attrs) => [
            'is_agent' => true,
        ]);
    }

    public function user(): static
    {
        return $this->state(fn(array $attrs) => [
            'is_agent' => false,
        ]);
    }
}
