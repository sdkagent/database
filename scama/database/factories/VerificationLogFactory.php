<?php

namespace Database\Factories;

use App\Models\Security\VerificationLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class VerificationLogFactory extends Factory
{
    protected $model = VerificationLog::class;

    public function definition(): array
    {
        return [
            'activation_id'    => \App\Models\LicenseActivation::factory(),
            'license_key'      => fake()->optional()->bothify('LIC-??????????????????????'),
            'api_key'          => fake()->optional()->sha256(),
            'ip_address'       => fake()->ipv4(),
            'user_agent'       => fake()->optional()->userAgent(),
            'request_domain'   => fake()->optional()->domainName(),
            'request_ip'       => fake()->optional()->ipv4(),
            'tier1_api'        => fake()->randomElement(['pass', 'fail', 'na']),
            'tier2_license'    => fake()->randomElement(['pass', 'fail', 'na']),
            'tier3_domain'     => fake()->randomElement(['pass', 'fail', 'na']),
            'tier4_ip'         => fake()->randomElement(['pass', 'fail', 'na']),
            'tier5_subscription' => fake()->randomElement(['pass', 'fail', 'na']),
            'overall_result'   => fake()->optional()->randomElement(['valid', 'invalid', 'expired', 'suspicious', 'banned']),
        ];
    }

    public function valid(): static
    {
        return $this->state(fn(array $attrs) => [
            'overall_result' => 'valid',
            'tier1_api'      => 'pass',
            'tier2_license'  => 'pass',
            'tier3_domain'   => 'pass',
            'tier4_ip'       => 'pass',
        ]);
    }

    public function invalid(): static
    {
        return $this->state(fn(array $attrs) => [
            'overall_result' => 'invalid',
        ]);
    }

    public function suspicious(): static
    {
        return $this->state(fn(array $attrs) => [
            'overall_result' => 'suspicious',
        ]);
    }

    public function tier1_api_pass(): static
    {
        return $this->state(['tier1_api' => 'pass']);
    }

    public function tier1_api_fail(): static
    {
        return $this->state(['tier1_api' => 'fail']);
    }

    public function tier1_api_na(): static
    {
        return $this->state(['tier1_api' => 'na']);
    }

    public function tier2_license_pass(): static
    {
        return $this->state(['tier2_license' => 'pass']);
    }

    public function tier2_license_fail(): static
    {
        return $this->state(['tier2_license' => 'fail']);
    }

    public function tier2_license_na(): static
    {
        return $this->state(['tier2_license' => 'na']);
    }

    public function tier3_domain_pass(): static
    {
        return $this->state(['tier3_domain' => 'pass']);
    }

    public function tier3_domain_fail(): static
    {
        return $this->state(['tier3_domain' => 'fail']);
    }

    public function tier3_domain_na(): static
    {
        return $this->state(['tier3_domain' => 'na']);
    }

    public function tier4_ip_pass(): static
    {
        return $this->state(['tier4_ip' => 'pass']);
    }

    public function tier4_ip_fail(): static
    {
        return $this->state(['tier4_ip' => 'fail']);
    }

    public function tier4_ip_na(): static
    {
        return $this->state(['tier4_ip' => 'na']);
    }

    public function tier5_subscription_pass(): static
    {
        return $this->state(['tier5_subscription' => 'pass']);
    }

    public function tier5_subscription_fail(): static
    {
        return $this->state(['tier5_subscription' => 'fail']);
    }

    public function tier5_subscription_na(): static
    {
        return $this->state(['tier5_subscription' => 'na']);
    }
}
