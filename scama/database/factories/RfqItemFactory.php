<?php

namespace Database\Factories;

use App\Models\Procurement\RfqItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Procurement\Rfq;
use App\Models\Product\Product;


class RfqItemFactory extends Factory
{
    protected $model = RfqItem::class;

    public function definition(): array
    {
        return [
            'rfq_id' => Rfq::factory(),
            'product_id' => Product::factory(),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'notes' => fake()->sentence(),
            'line_order' => fake()->numberBetween(0, 100),
        ];
    }

}
