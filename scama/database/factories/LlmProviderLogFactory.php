<?php

namespace Database\Factories;

use App\Models\Llm\LlmProviderLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class LlmProviderLogFactory extends Factory
{
    protected $model = LlmProviderLog::class;

    public function definition(): array
    {
        return [
            'provider_id' => \App\Models\LlmProvider::factory(),
            'user_id'     => \App\Models\User::factory(),
            'prompt'      => fake()->paragraph(),
            'response'    => fake()->paragraphs(2, true),
        ];
    }
}
