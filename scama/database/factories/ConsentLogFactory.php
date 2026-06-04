<?php

namespace Database\Factories;

use App\Models\Gdpr\ConsentLog;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsentLogFactory extends Factory
{
    protected $model = ConsentLog::class;

    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'consent_type' => fake()->randomElement(['marketing', 'analytics', 'functional', 'third_party', 'cookies']),
            'purpose'      => fake()->sentence(),
            'granted'      => fake()->boolean(),
            'ip_address'   => fake()->ipv4(),
            'user_agent'   => fake()->userAgent(),
        ];
    }

    public function consent_type_analytics(): static
    {
        return $this->state(['consent_type' => 'analytics']);
    }

    public function consent_type_cookies(): static
    {
        return $this->state(['consent_type' => 'cookies']);
    }

    public function consent_type_functional(): static
    {
        return $this->state(['consent_type' => 'functional']);
    }

    public function consent_type_marketing(): static
    {
        return $this->state(['consent_type' => 'marketing']);
    }

    public function consent_type_third_party(): static
    {
        return $this->state(['consent_type' => 'third_party']);
    }

    public function granted(): static
    {
        return $this->state(fn(array $attrs) => ['granted' => true]);
    }

    public function denied(): static
    {
        return $this->state(fn(array $attrs) => ['granted' => false]);
    }
}
