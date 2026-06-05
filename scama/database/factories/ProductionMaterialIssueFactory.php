<?php

namespace Database\Factories;

use App\Models\Production\ProductionMaterialIssue;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inventory\StockItem;
use App\Models\Inventory\WarehouseLocation;
use App\Models\Product\Product;
use App\Models\Production\ProductionOrder;


class ProductionMaterialIssueFactory extends Factory
{
    protected $model = ProductionMaterialIssue::class;

    public function definition(): array
    {
        return [
            'production_order_id' => ProductionOrder::factory(),
            'stock_item_id' => StockItem::factory(),
            'product_id' => Product::factory(),
            'warehouse_location_id' => WarehouseLocation::factory(),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'unit_cost' => fake()->randomFloat(2, 0, 1000),
        ];
    }

}
