<?php

namespace Database\Factories;

use App\Models\Production\BillOfMaterial;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillOfMaterialFactory extends Factory
{
    protected $model = BillOfMaterial::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => fake()->name(),
            'version' => fake()->numberBetween(0, 100),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'is_active' => fake()->boolean(),
        ];
    }

}
