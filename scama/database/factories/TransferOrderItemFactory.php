<?php

namespace Database\Factories;

use App\Models\Inventory\TransferOrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferOrderItemFactory extends Factory
{
    protected $model = TransferOrderItem::class;

    public function definition(): array
    {
        return [
            'transfer_order_id' => TransferOrder::factory(),
            'product_id' => Product::factory(),
            'stock_item_id' => StockItem::factory(),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'received_qty' => fake()->numberBetween(0, 100),
            'unit_cost' => fake()->randomFloat(2, 0, 1000),
        ];
    }

}
