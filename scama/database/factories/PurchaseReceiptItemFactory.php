<?php

namespace Database\Factories;

use App\Models\Procurement\PurchaseReceiptItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseReceiptItemFactory extends Factory
{
    protected $model = PurchaseReceiptItem::class;

    public function definition(): array
    {
        return [
            'purchase_receipt_id' => PurchaseReceipt::factory(),
            'po_item_id' => PurchaseOrderItem::factory(),
            'product_id' => Product::factory(),
            'warehouse_location_id' => WarehouseLocation::factory(),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'unit_cost' => fake()->randomFloat(2, 0, 1000),
            'batch_number' => fake()->unique()->bothify('BATCH-####'),
        ];
    }

}
