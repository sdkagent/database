<?php

namespace Database\Factories;

use App\Models\Billing\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'user_id'         => \App\Models\User::factory(),
            'order_id'        => \App\Models\Order::factory(),
            'subscription_id' => \App\Models\UserSubscription::factory(),
            'invoice_number'  => fake()->unique()->bothify('INV-####-????'),
            'total'           => fake()->randomFloat(2, 10, 2000),
            'tax'             => fake()->randomFloat(2, 0, 200),
            'status'          => fake()->randomElement(['draft', 'open', 'paid', 'void', 'refunded']),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'draft',
        ]);
    }

    public function open(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'open',
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'paid',
        ]);
    }

    public function void(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'void',
        ]);
    }

    public function refunded(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'refunded',
        ]);
    }
}
