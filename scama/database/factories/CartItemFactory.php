<?php

namespace Database\Factories;

use App\Models\Commerce\CartItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        $unitPrice = fake()->randomFloat(2, 5, 200);
        $quantity = fake()->numberBetween(1, 5);
        return [
            'cart_id'    => \App\Models\Cart::factory(),
            'product_id' => \App\Models\Product::factory(),
            'plan_id'    => \App\Models\SubscriptionPlan::factory(),
            'quantity'   => $quantity,
            'unit_price' => $unitPrice,
            'subtotal'   => $unitPrice * $quantity,
        ];
    }
}
