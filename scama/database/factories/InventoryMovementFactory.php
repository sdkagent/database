<?php

namespace Database\Factories;

use App\Models\Inventory\InventoryMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryMovementFactory extends Factory
{
    protected $model = InventoryMovement::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'from_location_id' => WarehouseLocation::factory(),
            'to_location_id' => WarehouseLocation::factory(),
            'stock_item_id' => StockItem::factory(),
            'movement_type' => fake()->randomElement(['receipt', 'issue', 'transfer', 'adjustment', 'return', 'sale']),
            'reference_type' => fake()->randomElement(['purchase_order', 'transfer_order', 'sale', 'adjustment']),
            'reference_id' => fake()->numberBetween(1, 1000),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'unit_cost' => fake()->randomFloat(2, 0, 1000),
            'notes' => fake()->sentence(),
            'created_by' => User::factory(),
        ];
    }

    public function movement_type_receipt(): static
    {
        return $this->state(['movement_type' => 'receipt']);
    }

    public function movement_type_issue(): static
    {
        return $this->state(['movement_type' => 'issue']);
    }

    public function movement_type_transfer(): static
    {
        return $this->state(['movement_type' => 'transfer']);
    }

    public function movement_type_adjustment(): static
    {
        return $this->state(['movement_type' => 'adjustment']);
    }

    public function movement_type_return(): static
    {
        return $this->state(['movement_type' => 'return']);
    }

    public function movement_type_sale(): static
    {
        return $this->state(['movement_type' => 'sale']);
    }

}
