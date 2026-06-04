<?php

namespace Database\Factories;

use App\Models\Procurement\SupplierProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierProductFactory extends Factory
{
    protected $model = SupplierProduct::class;

    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'product_id' => Product::factory(),
            'supplier_sku' => fake()->unique()->bothify('SKU-####'),
            'lead_time_days' => fake()->numberBetween(0, 100),
            'moq' => fake()->numberBetween(1, 1000),
            'is_preferred' => fake()->boolean(),
        ];
    }

}
