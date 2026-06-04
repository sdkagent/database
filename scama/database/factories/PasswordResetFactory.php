<?php

namespace Database\Factories;

use App\Models\Auth\PasswordReset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PasswordResetFactory extends Factory
{
    protected $model = PasswordReset::class;

    public function definition(): array
    {
        return [
            'user_id'    => \App\Models\User::factory(),
            'token'      => Str::random(60),
            'type'       => fake()->randomElement(['password', 'email']),
            'expires_at' => fake()->dateTimeBetween('now', '+1 hour'),
            'used_at'    => fake()->optional(0.3)->dateTimeThisMonth(),
        ];
    }

    public function password(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'password',
        ]);
    }

    public function email(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'email',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attrs) => [
            'expires_at' => now()->subHour(),
        ]);
    }

    public function used(): static
    {
        return $this->state(fn(array $attrs) => [
            'used_at' => now(),
        ]);
    }
}
