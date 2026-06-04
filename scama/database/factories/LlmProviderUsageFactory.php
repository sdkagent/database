<?php

namespace Database\Factories;

use App\Models\Llm\LlmProviderUsage;
use Illuminate\Database\Eloquent\Factories\Factory;

class LlmProviderUsageFactory extends Factory
{
    protected $model = LlmProviderUsage::class;

    public function definition(): array
    {
        return [
            'provider_id' => \App\Models\LlmProvider::factory(),
            'user_id'     => \App\Models\User::factory(),
            'tokens_used' => fake()->numberBetween(10, 100000),
        ];
    }
}
