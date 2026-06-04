<?php

namespace Database\Factories;

use App\Models\Licensing\LicenseActivation;
use Illuminate\Database\Eloquent\Factories\Factory;

class LicenseActivationFactory extends Factory
{
    protected $model = LicenseActivation::class;

    public function definition(): array
    {
        return [
            'license_id'       => \App\Models\License::factory(),
            'domain'           => fake()->optional()->domainName(),
            'hosting_ip'       => fake()->optional()->ipv4(),
            'status'           => fake()->randomElement(['active', 'inactive', 'suspicious', 'banned']),
            'last_verified_at' => fake()->dateTimeThisMonth(),
            'meta'             => [],
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'inactive',
        ]);
    }

    public function suspicious(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'suspicious',
        ]);
    }

    public function banned(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'banned',
        ]);
    }
}
