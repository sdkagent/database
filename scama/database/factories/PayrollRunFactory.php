<?php

namespace Database\Factories;

use App\Models\Hr\PayrollRun;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Accounting\AccountPeriod;
use App\Models\Accounting\FiscalYear;
use App\Models\Auth\User;


class PayrollRunFactory extends Factory
{
    protected $model = PayrollRun::class;

    public function definition(): array
    {
        return [
            'fiscal_year_id' => FiscalYear::factory(),
            'account_period_id' => AccountPeriod::factory(),
            'run_number' => fake()->unique()->bothify('PR-####'),
            'period_start' => fake()->date(),
            'period_end' => fake()->date(),
            'status' => fake()->randomElement(['draft', 'processing', 'completed', 'cancelled']),
            'total_gross' => fake()->randomFloat(2, 0, 1000),
            'total_deductions' => fake()->randomFloat(2, 0, 1000),
            'total_net' => fake()->randomFloat(2, 0, 1000),
            'notes' => fake()->sentence(),
            'processed_by' => User::factory(),
        ];
    }

    public function status_draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function status_processing(): static
    {
        return $this->state(['status' => 'processing']);
    }

    public function status_completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function status_cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

}
