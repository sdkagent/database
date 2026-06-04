<?php

namespace Database\Factories;

use App\Models\Product\Wishlist;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistFactory extends Factory
{
    protected $model = Wishlist::class;

    public function definition(): array
    {
        return [
            'user_id'    => \App\Models\User::factory(),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
