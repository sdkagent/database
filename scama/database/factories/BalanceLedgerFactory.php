<?php

namespace Database\Factories;

use App\Models\Accounting\BalanceLedger;
use Illuminate\Database\Eloquent\Factories\Factory;

class BalanceLedgerFactory extends Factory
{
    protected $model = BalanceLedger::class;

    public function definition(): array
    {
        $amount = fake()->randomFloat(4, -1000, 1000);
        return [
            'seller_id'      => \App\Models\SellerProfile::factory(),
            'type'           => fake()->randomElement(['sale_credit', 'commission_earned', 'payout_debit', 'adjustment', 'fee']),
            'amount'         => $amount,
            'balance_before' => fake()->randomFloat(4, 0, 5000),
            'balance_after'  => fake()->randomFloat(4, 0, 5000),
            'reference_type' => fake()->optional()->randomElement(['order', 'payout', 'refund']),
            'reference_id'   => fake()->optional()->numberBetween(1, 1000),
            'description'    => fake()->optional()->sentence(),
        ];
    }

    public function saleCredit(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'sale_credit',
        ]);
    }

    public function payoutDebit(): static
    {
        return $this->state(fn(array $attrs) => [
            'type'   => 'payout_debit',
            'amount' => -abs($attrs['amount'] ?? -100),
        ]);
    }

    public function adjustment(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'adjustment',
        ]);
    }

    public function typeCommissionEarned(): static
    {
        return $this->state(['type' => 'commission_earned']);
    }

    public function typeFee(): static
    {
        return $this->state(['type' => 'fee']);
    }
}
