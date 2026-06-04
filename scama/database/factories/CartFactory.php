<?php

namespace Database\Factories;

use App\Models\Commerce\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'user_id'    => \App\Models\User::factory(),
            'coupon_id'  => \App\Models\Coupon::factory(),
            'subtotal'   => fake()->randomFloat(2, 10, 500),
            'tax'        => fake()->randomFloat(2, 0, 50),
            'total'      => fake()->randomFloat(2, 10, 550),
            'expires_at' => fake()->optional()->dateTimeBetween('+1 hour', '+1 day'),
        ];
    }
}
