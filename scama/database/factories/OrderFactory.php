<?php

namespace Database\Factories;

use App\Models\Commerce\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id'             => \App\Models\User::factory(),
            'order_number'        => fake()->unique()->bothify('ORD-####-????'),
            'status'              => fake()->randomElement(['pending', 'confirmed', 'processing', 'completed', 'cancelled', 'refunded']),
            'subtotal'            => fake()->randomFloat(2, 10, 500),
            'tax'                 => fake()->randomFloat(2, 0, 50),
            'discount_total'      => fake()->randomFloat(2, 0, 50),
            'total'               => fake()->randomFloat(2, 10, 550),
            'currency'            => fake()->randomElement(['USD', 'EUR', 'GBP']),
            'notes'               => fake()->optional()->sentence(),
            'billing_address_id'  => null,
            'shipping_address_id' => null,
            'coupon_id'           => \App\Models\Coupon::factory(),
            'api_client_id'       => \App\Models\ApiClient::factory(),
            'customer_notes'      => fake()->optional()->sentence(),
            'ip_address'          => fake()->optional()->ipv4(),
            'user_agent'          => fake()->optional()->userAgent(),
            'paid_at'             => fake()->optional(0.5)->dateTimeThisMonth(),
            'cancelled_at'        => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'pending',
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'confirmed',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'completed',
            'paid_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    public function refunded(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'refunded',
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'processing',
        ]);
    }

    public function usd(): static
    {
        return $this->state(fn(array $attrs) => [
            'currency' => 'USD',
        ]);
    }

    public function eur(): static
    {
        return $this->state(fn(array $attrs) => [
            'currency' => 'EUR',
        ]);
    }

    public function gbp(): static
    {
        return $this->state(fn(array $attrs) => [
            'currency' => 'GBP',
        ]);
    }
}
