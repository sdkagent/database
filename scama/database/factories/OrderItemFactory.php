<?php

namespace Database\Factories;

use App\Models\Commerce\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $unitPrice = fake()->randomFloat(2, 5, 200);
        $quantity = fake()->numberBetween(1, 5);
        return [
            'order_id'   => \App\Models\Order::factory(),
            'product_id' => \App\Models\Product::factory(),
            'plan_id'    => \App\Models\SubscriptionPlan::factory(),
            'item_type'  => fake()->randomElement(['product', 'subscription']),
            'name'       => fake()->words(3, true),
            'quantity'   => $quantity,
            'unit_price' => $unitPrice,
            'subtotal'   => $unitPrice * $quantity,
        ];
    }

    public function product(): static
    {
        return $this->state(fn(array $attrs) => [
            'item_type' => 'product',
        ]);
    }

    public function subscription(): static
    {
        return $this->state(fn(array $attrs) => [
            'item_type' => 'subscription',
        ]);
    }
}
