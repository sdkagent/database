<?php

namespace Database\Factories;

use App\Models\Auth\AuthLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthLogFactory extends Factory
{
    protected $model = AuthLog::class;

    public function definition(): array
    {
        return [
            'user_id'           => \App\Models\User::factory(),
            'type'              => fake()->randomElement(['login', 'logout', 'failed_login', '2fa_attempt', 'magic_link', 'password_reset']),
            'ip_address'        => fake()->ipv4(),
            'device_fingerprint' => fake()->optional()->sha256(),
            'status'            => fake()->randomElement(['success', 'failed', 'suspicious']),
            'details'           => [],
        ];
    }

    public function login(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'login',
        ]);
    }

    public function logout(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'logout',
        ]);
    }

    public function failedLogin(): static
    {
        return $this->state(fn(array $attrs) => [
            'type'   => 'failed_login',
            'status' => 'failed',
        ]);
    }

    public function success(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'success',
        ]);
    }

    public function suspicious(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'suspicious',
        ]);
    }

    public function statusFailed(): static
    {
        return $this->state(['status' => 'failed']);
    }

    public function type2faAttempt(): static
    {
        return $this->state(['type' => '2fa_attempt']);
    }

    public function typeMagicLink(): static
    {
        return $this->state(['type' => 'magic_link']);
    }

    public function typePasswordReset(): static
    {
        return $this->state(['type' => 'password_reset']);
    }
}
