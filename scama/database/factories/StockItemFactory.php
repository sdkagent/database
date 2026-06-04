<?php

namespace Database\Factories;

use App\Models\Inventory\StockItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockItemFactory extends Factory
{
    protected $model = StockItem::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'warehouse_location_id' => WarehouseLocation::factory(),
            'serial_number' => fake()->unique()->bothify('SN-####-????'),
            'batch_number' => fake()->unique()->bothify('LOT-####'),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'reserved_quantity' => fake()->randomNumber(3),
            'unit_cost' => fake()->randomFloat(2, 0, 1000),
            'status' => fake()->randomElement(['available', 'reserved', 'quarantine', 'damaged', 'disposed']),
        ];
    }

    public function status_available(): static
    {
        return $this->state(['status' => 'available']);
    }

    public function status_reserved(): static
    {
        return $this->state(['status' => 'reserved']);
    }

    public function status_quarantine(): static
    {
        return $this->state(['status' => 'quarantine']);
    }

    public function status_damaged(): static
    {
        return $this->state(['status' => 'damaged']);
    }

    public function status_disposed(): static
    {
        return $this->state(['status' => 'disposed']);
    }

}
