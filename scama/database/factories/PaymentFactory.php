<?php

namespace Database\Factories;

use App\Models\Billing\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'invoice_id'     => \App\Models\Invoice::factory(),
            'gateway'        => fake()->randomElement(['stripe', 'paypal', 'bank_transfer']),
            'transaction_id' => fake()->optional()->uuid(),
            'amount'         => fake()->randomFloat(2, 10, 2000),
            'status'         => fake()->randomElement(['pending', 'success', 'failed']),
            'meta'           => [],
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'pending',
        ]);
    }

    public function success(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'success',
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'failed',
        ]);
    }

    public function stripe(): static
    {
        return $this->state(fn(array $attrs) => [
            'gateway' => 'stripe',
        ]);
    }

    public function paypal(): static
    {
        return $this->state(fn(array $attrs) => [
            'gateway' => 'paypal',
        ]);
    }

    public function bankTransfer(): static
    {
        return $this->state(fn(array $attrs) => [
            'gateway' => 'bank_transfer',
        ]);
    }
}
