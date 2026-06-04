<?php

namespace Database\Factories;

use App\Models\Inventory\WarehouseLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseLocationFactory extends Factory
{
    protected $model = WarehouseLocation::class;

    public function definition(): array
    {
        return [
            'warehouse_id' => Warehouse::factory(),
            'parent_id' => WarehouseLocation::factory(),
            'code' => fake()->unique()->bothify('??-####'),
            'name' => fake()->name(),
            'type' => fake()->randomElement(['aisle', 'rack', 'shelf', 'bin', 'bulk']),
            'max_weight' => fake()->randomFloat(2, 10, 1000),
            'max_volume' => fake()->randomFloat(2, 1, 100),
            'is_active' => fake()->boolean(),
        ];
    }

    public function type_aisle(): static
    {
        return $this->state(['type' => 'aisle']);
    }

    public function type_rack(): static
    {
        return $this->state(['type' => 'rack']);
    }

    public function type_shelf(): static
    {
        return $this->state(['type' => 'shelf']);
    }

    public function type_bin(): static
    {
        return $this->state(['type' => 'bin']);
    }

    public function type_bulk(): static
    {
        return $this->state(['type' => 'bulk']);
    }

}
