<?php

namespace Database\Factories;

use App\Models\Production\BomItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product\Product;
use App\Models\Production\BillOfMaterial;


class BomItemFactory extends Factory
{
    protected $model = BomItem::class;

    public function definition(): array
    {
        return [
            'bom_id' => BillOfMaterial::factory(),
            'component_id' => Product::factory(),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'unit' => fake()->randomElement(['pcs', 'kg', 'm', 'l', 'box']),
            'scrap_rate' => fake()->randomFloat(2, 0, 1000),
            'line_order' => fake()->numberBetween(0, 100),
        ];
    }

}
