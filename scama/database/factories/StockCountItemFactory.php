<?php

namespace Database\Factories;

use App\Models\Inventory\StockCountItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockCountItemFactory extends Factory
{
    protected $model = StockCountItem::class;

    public function definition(): array
    {
        return [
            'stock_count_id' => StockCount::factory(),
            'product_id' => Product::factory(),
            'location_id' => WarehouseLocation::factory(),
            'expected_qty' => fake()->numberBetween(0, 100),
            'counted_qty' => fake()->numberBetween(0, 100),
            'difference' => fake()->randomFloat(2, 0, 1000),
            'notes' => fake()->sentence(),
        ];
    }

}
