<?php

namespace Database\Factories;

use App\Models\Inventory\StockCountItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inventory\StockCount;
use App\Models\Inventory\WarehouseLocation;
use App\Models\Product\Product;


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
