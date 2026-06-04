<?php

namespace Database\Factories;

use App\Models\Commerce\OrderItemMetadata;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemMetadataFactory extends Factory
{
    protected $model = OrderItemMetadata::class;

    public function definition(): array
    {
        return [
            'order_item_id' => \App\Models\OrderItem::factory(),
            'license_id'    => \App\Models\License::factory(),
            'meta_key'      => fake()->randomElement(['color', 'size', 'weight', 'material', 'warranty']),
            'meta_value'    => fake()->sentence(),
        ];
    }
}
