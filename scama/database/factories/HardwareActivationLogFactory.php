<?php

namespace Database\Factories;

use App\Models\Licensing\HardwareActivationLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class HardwareActivationLogFactory extends Factory
{
    protected $model = HardwareActivationLog::class;

    public function definition(): array
    {
        return [
            'hardware_activation_id' => \App\Models\HardwareActivation::factory(),
            'license_id'             => \App\Models\License::factory(),
            'machine_id'             => strtoupper(dechex(fake()->numberBetween(100000, 999999))),
            'hardware_snapshot'      => [],
            'system_specs'           => [],
            'activation_status'      => fake()->randomElement(['success', 'failed', 'hardware_mismatch', 'limit_exceeded', 'suspicious']),
            'failure_reason'         => fake()->optional()->sentence(),
            'vm_detected'            => fake()->boolean(10),
            'tamper_detected'        => fake()->boolean(5),
            'compatibility_result'   => fake()->randomElement(['compatible', 'incompatible', 'unknown']),
        ];
    }

    public function success(): static
    {
        return $this->state(fn(array $attrs) => [
            'activation_status' => 'success',
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attrs) => [
            'activation_status' => 'failed',
        ]);
    }

    public function hardwareMismatch(): static
    {
        return $this->state(fn(array $attrs) => [
            'activation_status' => 'hardware_mismatch',
        ]);
    }

    public function limitExceeded(): static
    {
        return $this->state(fn(array $attrs) => [
            'activation_status' => 'limit_exceeded',
        ]);
    }

    public function suspicious(): static
    {
        return $this->state(fn(array $attrs) => [
            'activation_status' => 'suspicious',
        ]);
    }

    public function compatibility_result_compatible(): static
    {
        return $this->state(['compatibility_result' => 'compatible']);
    }

    public function compatibility_result_incompatible(): static
    {
        return $this->state(['compatibility_result' => 'incompatible']);
    }

    public function compatibility_result_unknown(): static
    {
        return $this->state(['compatibility_result' => 'unknown']);
    }
}
