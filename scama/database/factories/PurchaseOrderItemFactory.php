<?php

namespace Database\Factories;

use App\Models\Procurement\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inventory\WarehouseLocation;
use App\Models\Procurement\PurchaseOrder;
use App\Models\Product\Product;


class PurchaseOrderItemFactory extends Factory
{
    protected $model = PurchaseOrderItem::class;

    public function definition(): array
    {
        return [
            'purchase_order_id' => PurchaseOrder::factory(),
            'product_id' => Product::factory(),
            'warehouse_location_id' => WarehouseLocation::factory(),
            'description' => fake()->sentence(),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'received_qty' => fake()->numberBetween(0, 100),
            'unit_price' => fake()->randomFloat(2, 0, 1000),
            'tax_rate' => fake()->randomFloat(2, 0, 1000),
            'subtotal' => fake()->randomFloat(2, 0, 1000),
            'line_order' => fake()->numberBetween(0, 100),
        ];
    }

}
