<?php

namespace Database\Factories;

use App\Models\Llm\BotConfig;
use Illuminate\Database\Eloquent\Factories\Factory;

class BotConfigFactory extends Factory
{
    protected $model = BotConfig::class;

    public function definition(): array
    {
        return [
            'name'                   => fake()->unique()->randomElement(['Support', 'Sales', 'Info', 'Help']) . '-bot',
            'platform'               => fake()->randomElement(['telegram', 'discord', 'slack', 'whatsapp', 'custom']),
            'platform_token'         => fake()->sha256(),
            'platform_username'      => fake()->userName(),
            'webhook_url'            => fake()->optional()->url(),
            'llm_provider_id'        => \App\Models\LlmProvider::factory(),
            'llm_system_prompt'      => 'You are a helpful assistant.',
            'welcome_message'        => fake()->sentence(),
            'status'                 => fake()->randomElement(['active', 'inactive']),
            'allowed_user_ids'       => null,
            'rate_limit_per_minute'  => fake()->numberBetween(10, 100),
            'max_conversation_length' => fake()->numberBetween(10, 200),
            'settings'               => ['language' => 'en', 'timezone' => 'UTC'],
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

    public function platformCustom(): static
    {
        return $this->state(['platform' => 'custom']);
    }

    public function platformDiscord(): static
    {
        return $this->state(['platform' => 'discord']);
    }

    public function platformSlack(): static
    {
        return $this->state(['platform' => 'slack']);
    }

    public function platformTelegram(): static
    {
        return $this->state(['platform' => 'telegram']);
    }

    public function platformWhatsapp(): static
    {
        return $this->state(['platform' => 'whatsapp']);
    }
}
