<?php

namespace Database\Factories;

use App\Models\Commerce\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition(): array
    {
        return [
            'order_id'        => \App\Models\Order::factory(),
            'tracking_number' => fake()->optional()->bothify('TRK-########'),
            'carrier'         => fake()->optional()->randomElement(['UPS', 'FedEx', 'USPS', 'DHL']),
            'status'          => fake()->randomElement(['pending', 'shipped', 'delivered', 'returned']),
            'shipped_at'      => fake()->optional(0.5)->dateTimeThisMonth(),
            'delivered_at'    => fake()->optional(0.3)->dateTimeThisMonth(),
            'shipping_data'   => [],
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'pending',
        ]);
    }

    public function shipped(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'     => 'shipped',
            'shipped_at' => now(),
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'       => 'delivered',
            'shipped_at'   => now()->subDays(3),
            'delivered_at' => now(),
        ]);
    }

    public function returned(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'returned',
        ]);
    }
}
