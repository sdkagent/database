<?php

namespace Database\Factories;

use App\Models\Llm\LlmProviderActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

class LlmProviderActivityFactory extends Factory
{
    protected $model = LlmProviderActivity::class;

    public function definition(): array
    {
        return [
            'provider_id'   => \App\Models\LlmProvider::factory(),
            'user_id'        => \App\Models\User::factory(),
            'activity_type'  => fake()->randomElement(['prompt', 'response', 'error']),
            'details'        => ['model' => 'gpt-4', 'tokens' => fake()->numberBetween(10, 1000)],
        ];
    }

    public function activity_type_prompt(): static
    {
        return $this->state(['activity_type' => 'prompt']);
    }

    public function activity_type_response(): static
    {
        return $this->state(['activity_type' => 'response']);
    }

    public function activity_type_error(): static
    {
        return $this->state(['activity_type' => 'error']);
    }
}
