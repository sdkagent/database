<?php

namespace Database\Factories;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'role'              => fake()->randomElement(['admin', 'user', 'seller', 'support']),
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => fake()->optional()->dateTimeThisYear(),
            'password'          => bcrypt('password'),
            'phone'             => fake()->optional()->phoneNumber(),
            'status'            => fake()->randomElement(['active', 'suspended', 'pending', 'banned']),
            'ip_whitelist'      => [],
            'telegram_chat_id'  => fake()->optional()->numerify('##########'),
            'settings'          => [],
            'avatar_url'        => fake()->optional()->imageUrl(),
            'last_login_at'     => fake()->optional()->dateTimeThisMonth(),
            'locale'            => fake()->randomElement(['en', 'es', 'fr', 'de']),
            'timezone'          => fake()->randomElement(['UTC', 'America/New_York', 'Europe/London']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'active',
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'suspended',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'pending',
        ]);
    }

    public function banned(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'banned',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attrs) => [
            'role' => 'admin',
        ]);
    }

    public function seller(): static
    {
        return $this->state(fn(array $attrs) => [
            'role' => 'seller',
        ]);
    }

    public function role_user(): static
    {
        return $this->state(['role' => 'user']);
    }

    public function role_support(): static
    {
        return $this->state(['role' => 'support']);
    }

    public function locale_en(): static
    {
        return $this->state(['locale' => 'en']);
    }

    public function locale_es(): static
    {
        return $this->state(['locale' => 'es']);
    }

    public function locale_fr(): static
    {
        return $this->state(['locale' => 'fr']);
    }

    public function locale_de(): static
    {
        return $this->state(['locale' => 'de']);
    }

    public function timezone_utc(): static
    {
        return $this->state(['timezone' => 'UTC']);
    }

    public function timezone_america_new_york(): static
    {
        return $this->state(['timezone' => 'America/New_York']);
    }

    public function timezone_europe_london(): static
    {
        return $this->state(['timezone' => 'Europe/London']);
    }
}
