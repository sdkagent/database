<?php

namespace Database\Factories;

use App\Models\Accounting\BudgetLine;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetLineFactory extends Factory
{
    protected $model = BudgetLine::class;

    public function definition(): array
    {
        return [
            'budget_id' => Budget::factory(),
            'account_id' => ChartOfAccount::factory(),
            'period_id' => AccountPeriod::factory(),
            'amount' => fake()->randomFloat(2, 0, 1000),
        ];
    }

}
