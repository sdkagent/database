<?php

namespace Database\Factories;

use App\Models\Accounting\AccountBalance;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Accounting\AccountPeriod;
use App\Models\Accounting\ChartOfAccount;
use App\Models\Accounting\FiscalYear;


class AccountBalanceFactory extends Factory
{
    protected $model = AccountBalance::class;

    public function definition(): array
    {
        return [
            'account_id' => ChartOfAccount::factory(),
            'fiscal_year_id' => FiscalYear::factory(),
            'account_period_id' => AccountPeriod::factory(),
            'period_type' => fake()->randomElement(['month', 'quarter', 'year']),
            'opening_balance' => fake()->randomFloat(2, 0, 10000),
            'period_debit' => fake()->randomFloat(2, 0, 10000),
            'period_credit' => fake()->randomFloat(2, 0, 10000),
            'closing_balance' => fake()->randomFloat(2, 0, 10000),
        ];
    }

    public function period_type_month(): static
    {
        return $this->state(['period_type' => 'month']);
    }

    public function period_type_quarter(): static
    {
        return $this->state(['period_type' => 'quarter']);
    }

    public function period_type_year(): static
    {
        return $this->state(['period_type' => 'year']);
    }

}
