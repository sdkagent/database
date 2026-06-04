<?php

namespace Database\Factories;

use App\Models\Security\FraudLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class FraudLogFactory extends Factory
{
    protected $model = FraudLog::class;

    public function definition(): array
    {
        return [
            'license_id'    => \App\Models\License::factory(),
            'activation_id' => \App\Models\LicenseActivation::factory(),
            'ip'            => fake()->optional()->ipv4(),
            'domain'        => fake()->optional()->domainName(),
            'reason'        => fake()->sentence(),
            'severity'      => fake()->randomElement(['low', 'medium', 'high', 'critical']),
            'action_taken'  => fake()->randomElement(['logged', 'suspended', 'revoked']),
        ];
    }

    public function low(): static
    {
        return $this->state(fn(array $attrs) => [
            'severity' => 'low',
        ]);
    }

    public function medium(): static
    {
        return $this->state(fn(array $attrs) => [
            'severity' => 'medium',
        ]);
    }

    public function high(): static
    {
        return $this->state(fn(array $attrs) => [
            'severity' => 'high',
        ]);
    }

    public function critical(): static
    {
        return $this->state(fn(array $attrs) => [
            'severity' => 'critical',
        ]);
    }

    public function action_taken_logged(): static
    {
        return $this->state(['action_taken' => 'logged']);
    }

    public function action_taken_revoked(): static
    {
        return $this->state(['action_taken' => 'revoked']);
    }

    public function action_taken_suspended(): static
    {
        return $this->state(['action_taken' => 'suspended']);
    }
}
