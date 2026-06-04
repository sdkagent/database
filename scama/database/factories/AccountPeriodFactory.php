<?php

namespace Database\Factories;

use App\Models\Accounting\AccountPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountPeriodFactory extends Factory
{
    protected $model = AccountPeriod::class;

    public function definition(): array
    {
        return [
            'fiscal_year_id' => FiscalYear::factory(),
            'type' => fake()->randomElement(['month', 'quarter', 'year']),
            'is_closed' => fake()->boolean(),
        ];
    }

    public function type_month(): static
    {
        return $this->state(['type' => 'month']);
    }

    public function type_quarter(): static
    {
        return $this->state(['type' => 'quarter']);
    }

    public function type_year(): static
    {
        return $this->state(['type' => 'year']);
    }

}
