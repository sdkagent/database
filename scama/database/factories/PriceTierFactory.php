<?php

namespace Database\Factories;

use App\Models\Pricing\PriceTier;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriceTierFactory extends Factory
{
    protected $model = PriceTier::class;

    public function definition(): array
    {
        return [
            'product_id'   => Product::factory(),
            'min_quantity' => fake()->numberBetween(1, 10),
            'max_quantity' => fake()->numberBetween(11, 100),
            'unit_price'   => fake()->randomFloat(2, 5, 500),
        ];
    }
}
