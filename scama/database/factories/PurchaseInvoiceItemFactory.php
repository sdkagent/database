<?php

namespace Database\Factories;

use App\Models\Procurement\PurchaseInvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseInvoiceItemFactory extends Factory
{
    protected $model = PurchaseInvoiceItem::class;

    public function definition(): array
    {
        return [
            'purchase_invoice_id' => PurchaseInvoice::factory(),
            'po_item_id' => PurchaseOrderItem::factory(),
            'product_id' => Product::factory(),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'unit_price' => fake()->randomFloat(2, 0, 1000),
            'subtotal' => fake()->randomFloat(2, 0, 1000),
        ];
    }

}
