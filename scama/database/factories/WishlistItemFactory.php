<?php

namespace Database\Factories;

use App\Models\Product\WishlistItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product\Product;
use App\Models\Product\Wishlist;


class WishlistItemFactory extends Factory
{
    protected $model = WishlistItem::class;

    public function definition(): array
    {
        return [
            'wishlist_id' => Wishlist::factory(),
            'product_id' => Product::factory(),
            'notes' => fake()->sentence(),
        ];
    }

}
