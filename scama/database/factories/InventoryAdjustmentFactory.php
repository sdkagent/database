<?php

namespace Database\Factories;

use App\Models\Inventory\InventoryAdjustment;
use App\Models\Product\Product;
use App\Models\Auth\User;
use App\Models\Inventory\WarehouseLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryAdjustmentFactory extends Factory
{
    protected $model = InventoryAdjustment::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'warehouse_location_id' => WarehouseLocation::factory(),
            'adjustment_type' => fake()->randomElement(['count', 'damage', 'write_off', 'return', 'reclassification']),
            'expected_qty' => fake()->numberBetween(0, 100),
            'actual_qty' => fake()->numberBetween(0, 100),
            'difference' => fake()->randomFloat(2, 0, 1000),
            'reason' => fake()->sentence(),
            'approved_by' => User::factory(),
        ];
    }

    public function adjustment_type_count(): static
    {
        return $this->state(['adjustment_type' => 'count']);
    }

    public function adjustment_type_damage(): static
    {
        return $this->state(['adjustment_type' => 'damage']);
    }

    public function adjustment_type_write_off(): static
    {
        return $this->state(['adjustment_type' => 'write_off']);
    }

    public function adjustment_type_return(): static
    {
        return $this->state(['adjustment_type' => 'return']);
    }

    public function adjustment_type_reclassification(): static
    {
        return $this->state(['adjustment_type' => 'reclassification']);
    }

}
