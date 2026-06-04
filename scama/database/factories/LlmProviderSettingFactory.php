<?php

namespace Database\Factories;

use App\Models\Llm\LlmProviderSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class LlmProviderSettingFactory extends Factory
{
    protected $model = LlmProviderSetting::class;

    public function definition(): array
    {
        return [
            'provider_id'   => \App\Models\LlmProvider::factory(),
            'setting_key'   => fake()->randomElement(['temperature', 'max_tokens', 'top_p', 'model']),
            'setting_value' => fake()->randomElement(['0.7', '2048', '0.9', 'gpt-4']),
        ];
    }
}
