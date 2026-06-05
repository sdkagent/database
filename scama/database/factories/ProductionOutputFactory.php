<?php

namespace Database\Factories;

use App\Models\Production\ProductionOutput;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inventory\WarehouseLocation;
use App\Models\Product\Product;
use App\Models\Production\ProductionOrder;


class ProductionOutputFactory extends Factory
{
    protected $model = ProductionOutput::class;

    public function definition(): array
    {
        return [
            'production_order_id' => ProductionOrder::factory(),
            'product_id' => Product::factory(),
            'warehouse_location_id' => WarehouseLocation::factory(),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'unit_cost' => fake()->randomFloat(2, 0, 1000),
            'batch_number' => fake()->unique()->bothify('BATCH-####'),
        ];
    }

}
