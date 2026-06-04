<?php

namespace Database\Factories;

use App\Models\Procurement\SupplierPricelist;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierPricelistFactory extends Factory
{
    protected $model = SupplierPricelist::class;

    public function definition(): array
    {
        return [
            'supplier_product_id' => SupplierProduct::factory(),
            'unit_price' => fake()->randomFloat(2, 0, 1000),
            'currency_id' => Currency::factory(),
            'min_quantity' => fake()->randomFloat(2, 0, 1000),
            'effective_from' => fake()->date(),
            'effective_until' => fake()->date(),
            'is_active' => fake()->boolean(),
        ];
    }

}
