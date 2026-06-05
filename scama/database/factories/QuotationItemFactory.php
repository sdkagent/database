<?php

namespace Database\Factories;

use App\Models\Procurement\QuotationItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Procurement\RfqItem;
use App\Models\Procurement\SupplierQuotation;
use App\Models\Product\Product;


class QuotationItemFactory extends Factory
{
    protected $model = QuotationItem::class;

    public function definition(): array
    {
        return [
            'quotation_id' => SupplierQuotation::factory(),
            'rfq_item_id' => RfqItem::factory(),
            'product_id' => Product::factory(),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'unit_price' => fake()->randomFloat(2, 0, 1000),
            'subtotal' => fake()->randomFloat(2, 0, 1000),
            'line_order' => fake()->numberBetween(0, 100),
        ];
    }

}
