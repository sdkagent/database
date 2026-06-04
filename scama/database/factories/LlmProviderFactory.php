<?php

namespace Database\Factories;

use App\Models\Llm\LlmProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class LlmProviderFactory extends Factory
{
    protected $model = LlmProvider::class;

    public function definition(): array
    {
        return [
            'name'     => fake()->randomElement(['OpenAI', 'Anthropic', 'Google AI', 'Cohere', 'HuggingFace']),
            'api_key'  => 'sk-' . fake()->sha256(),
            'base_url' => fake()->url(),
            'status'   => fake()->randomElement(['active', 'inactive']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'active']);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'inactive']);
    }
}
