<?php

namespace Database\Factories;

use App\Models\Sms\SmsProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmsProviderFactory extends Factory
{
    protected $model = SmsProvider::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'provider' => fake()->randomElement(['twilio', 'aws_sns', 'vonage', 'custom']),
            'api_key' => fake()->uuid(),
            'api_secret' => fake()->uuid(),
            'from_number' => fake()->phoneNumber(),
            'api_endpoint' => fake()->url(),
            'config' => json_encode([]),
            'is_active' => fake()->boolean(),
            'is_default' => fake()->boolean(),
            'priority' => fake()->numberBetween(0, 100),
        ];
    }

    public function provider_twilio(): static
    {
        return $this->state(['provider' => 'twilio']);
    }

    public function provider_aws_sns(): static
    {
        return $this->state(['provider' => 'aws_sns']);
    }

    public function provider_vonage(): static
    {
        return $this->state(['provider' => 'vonage']);
    }

    public function provider_custom(): static
    {
        return $this->state(['provider' => 'custom']);
    }

}
