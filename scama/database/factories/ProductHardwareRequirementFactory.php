<?php

namespace Database\Factories;

use App\Models\Product\ProductHardwareRequirement;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductHardwareRequirementFactory extends Factory
{
    protected $model = ProductHardwareRequirement::class;

    public function definition(): array
    {
        return [
            'product_id'       => \App\Models\Product::factory(),
            'os_name'          => fake()->optional()->randomElement(['Windows', 'macOS', 'Linux']),
            'os_version_min'   => fake()->optional()->randomElement(['10', '11', 'Big Sur', 'Ubuntu 20.04']),
            'cpu_cores_min'    => fake()->optional()->numberBetween(1, 8),
            'memory_mb_min'    => fake()->optional()->numberBetween(512, 16384),
            'disk_mb_min'      => fake()->optional()->numberBetween(100, 102400),
            'additional_notes' => fake()->optional()->sentence(),
        ];
    }
}
