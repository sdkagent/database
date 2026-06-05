<?php

namespace Database\Factories;

use App\Models\Product\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;
use App\Models\Commerce\Order;


class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'reviewable_type' => fake()->randomElement(['App\Models\Product', 'App\Models\Post', 'App\Models\Service']),
            'reviewable_id' => fake()->numberBetween(1, 100),
            'user_id' => User::factory(),
            'order_id' => Order::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'title' => fake()->sentence(),
            'body' => fake()->sentence(),
            'is_approved' => fake()->boolean(),
            'is_verified_purchase' => fake()->boolean(),
            'helpful_count' => fake()->numberBetween(0, 100),
        ];
    }

}
