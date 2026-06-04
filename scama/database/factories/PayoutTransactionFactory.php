<?php

namespace Database\Factories;

use App\Models\Seller\PayoutTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayoutTransactionFactory extends Factory
{
    protected $model = PayoutTransaction::class;

    public function definition(): array
    {
        $amount = fake()->randomFloat(2, 10, 5000);
        $fee = fake()->randomFloat(2, 0, $amount * 0.05);
        return [
            'seller_id'         => \App\Models\SellerProfile::factory(),
            'payout_account_id' => \App\Models\PayoutAccount::factory(),
            'amount'            => $amount,
            'fee'               => $fee,
            'net_amount'        => $amount - $fee,
            'currency'          => fake()->randomElement(['USD', 'EUR', 'GBP']),
            'period_start'      => fake()->optional()->date(),
            'period_end'        => fake()->optional()->date(),
            'status'            => fake()->randomElement(['pending', 'processing', 'completed', 'failed']),
            'reference'         => fake()->optional()->uuid(),
            'notes'             => fake()->optional()->sentence(),
            'processed_at'      => fake()->optional(0.5)->dateTimeThisMonth(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'pending',
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'processing',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn(array $attrs) => [
            'status'       => 'completed',
            'processed_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attrs) => [
            'status' => 'failed',
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
