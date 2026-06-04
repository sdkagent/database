<?php

namespace Database\Factories;

use App\Models\Licensing\HardwareActivation;
use Illuminate\Database\Eloquent\Factories\Factory;

class HardwareActivationFactory extends Factory
{
    protected $model = HardwareActivation::class;

    public function definition(): array
    {
        return [
            'license_id'          => \App\Models\License::factory(),
            'machine_id'          => strtoupper(dechex(fake()->unique()->numberBetween(100000, 999999))),
            'cpu_id'              => fake()->optional()->sha256(),
            'motherboard_serial'  => fake()->optional()->bothify('MB-########'),
            'bios_serial'         => fake()->optional()->bothify('BIOS-########'),
            'disk_serial'         => fake()->optional()->bothify('DISK-########'),
            'mac_address'         => fake()->optional()->macAddress(),
            'os_name'             => fake()->optional()->randomElement(['Windows 10', 'Windows 11', 'macOS Ventura', 'Ubuntu 22.04']),
            'os_version'          => fake()->optional()->semver(),
            'os_architecture'     => fake()->optional()->randomElement(['x64', 'x86', 'arm64']),
            'cpu_name'            => fake()->optional()->randomElement(['Intel Core i7', 'AMD Ryzen 5', 'Apple M1']),
            'cpu_cores'           => fake()->optional()->numberBetween(2, 16),
            'total_memory'        => fake()->optional()->numberBetween(4096, 65536),
            'system_manufacturer' => fake()->optional()->randomElement(['Dell', 'HP', 'Apple', 'Lenovo']),
            'system_model'        => fake()->optional()->word(),
            'local_ip'            => fake()->optional()->localIpv4(),
            'public_ip'           => fake()->optional()->ipv4(),
            'status'              => fake()->randomElement(['active', 'inactive', 'suspicious', 'banned']),
            'activation_limit'    => fake()->numberBetween(1, 5),
            'activated_at'        => fake()->dateTimeThisYear(),
            'last_ping_at'        => fake()->optional()->dateTimeThisMonth(),
            'required_os_min'     => fake()->optional()->randomElement(['Windows 10', 'macOS 11']),
            'required_memory_mb'  => fake()->optional()->numberBetween(1024, 16384),
            'required_disk_mb'    => fake()->optional()->numberBetween(500, 102400),
            'compatibility_status' => fake()->randomElement(['compatible', 'incompatible', 'unknown']),
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

    public function compatible(): static
    {
        return $this->state(fn(array $attrs) => [
            'compatibility_status' => 'compatible',
        ]);
    }

    public function compatibility_status_incompatible(): static
    {
        return $this->state(['compatibility_status' => 'incompatible']);
    }

    public function compatibility_status_unknown(): static
    {
        return $this->state(['compatibility_status' => 'unknown']);
    }
}
