<?php

namespace Database\Factories;

use App\Models\Billing\Refund;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefundFactory extends Factory
{
    protected $model = Refund::class;

    public function definition(): array
    {
        return [
            'order_id'      => \App\Models\Order::factory(),
            'payment_id'    => \App\Models\Payment::factory(),
            'amount'        => fake()->randomFloat(2, 5, 500),
            'reason'        => fake()->optional()->sentence(),
            'status'        => fake()->randomElement(['pending', 'approved', 'rejected', 'completed']),
            'processed_by'  => \App\Models\User::factory(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'pending',
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'approved',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'rejected',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'completed',
        ]);
    }
}
