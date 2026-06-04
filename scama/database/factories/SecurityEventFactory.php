<?php

namespace Database\Factories;

use App\Models\Security\SecurityEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class SecurityEventFactory extends Factory
{
    protected $model = SecurityEvent::class;

    public function definition(): array
    {
        return [
            'user_id'     => \App\Models\User::factory(),
            'event_type'  => fake()->randomElement(['login_failed', 'suspicious_activity', 'password_changed', '2fa_disabled']),
            'description' => fake()->sentence(),
            'ip_address'  => fake()->ipv4(),
            'user_agent'  => fake()->userAgent(),
        ];
    }

    public function event_type_login_failed(): static
    {
        return $this->state(['event_type' => 'login_failed']);
    }

    public function event_type_suspicious_activity(): static
    {
        return $this->state(['event_type' => 'suspicious_activity']);
    }

    public function event_type_password_changed(): static
    {
        return $this->state(['event_type' => 'password_changed']);
    }

    public function event_type_2fa_disabled(): static
    {
        return $this->state(['event_type' => '2fa_disabled']);
    }
}
