<?php

namespace Database\Factories;

use App\Models\Auth\UserTwoFa;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTwoFaFactory extends Factory
{
    protected $model = UserTwoFa::class;

    public function definition(): array
    {
        return [
            'user_id'      => \App\Models\User::factory(),
            'secret'       => fake()->optional()->sha256(),
            'method'       => fake()->randomElement(['totp', 'email', 'sms', 'backup_codes']),
            'backup_codes' => [],
            'is_enabled'   => fake()->boolean(30),
            'verified_at'  => fake()->optional(0.5)->dateTimeThisYear(),
        ];
    }

    public function totp(): static
    {
        return $this->state(fn(array $attrs) => [
            'method' => 'totp',
        ]);
    }

    public function email(): static
    {
        return $this->state(fn(array $attrs) => [
            'method' => 'email',
        ]);
    }

    public function sms(): static
    {
        return $this->state(fn(array $attrs) => [
            'method' => 'sms',
        ]);
    }

    public function enabled(): static
    {
        return $this->state(fn(array $attrs) => [
            'is_enabled' => true,
        ]);
    }

    public function disabled(): static
    {
        return $this->state(fn(array $attrs) => [
            'is_enabled' => false,
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn(array $attrs) => [
            'verified_at' => now(),
        ]);
    }

    public function method_backup_codes(): static
    {
        return $this->state(['method' => 'backup_codes']);
    }
}
