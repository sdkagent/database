<?php

namespace Database\Factories;

use App\Models\Llm\BotConversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class BotConversationFactory extends Factory
{
    protected $model = BotConversation::class;

    public function definition(): array
    {
        return [
            'user_id'       => \App\Models\User::factory(),
            'bot_config_id' => null,
            'message'       => fake()->sentence(),
            'response'      => fake()->optional()->paragraph(),
        ];
    }
}
