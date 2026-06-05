<?php

namespace Database\Factories;

use App\Models\Inventory\ReorderRule;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inventory\Warehouse;
use App\Models\Product\Product;


class ReorderRuleFactory extends Factory
{
    protected $model = ReorderRule::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'warehouse_id' => Warehouse::factory(),
            'min_quantity' => fake()->randomFloat(2, 0, 1000),
            'max_quantity' => fake()->randomFloat(2, 0, 1000),
            'reorder_point' => fake()->randomFloat(2, 0, 1000),
            'reorder_qty' => fake()->numberBetween(0, 100),
            'lead_time_days' => fake()->numberBetween(0, 100),
            'is_active' => fake()->boolean(),
        ];
    }

}
