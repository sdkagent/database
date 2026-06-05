<?php

namespace Database\Factories;

use App\Models\Hr\PayrollItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hr\Employee;
use App\Models\Hr\PayrollRun;


class PayrollItemFactory extends Factory
{
    protected $model = PayrollItem::class;

    public function definition(): array
    {
        return [
            'payroll_run_id' => PayrollRun::factory(),
            'employee_id' => Employee::factory(),
            'gross_pay' => fake()->randomFloat(2, 0, 1000),
            'total_deductions' => fake()->randomFloat(2, 0, 1000),
            'net_pay' => fake()->randomFloat(2, 0, 1000),
            'bank_account' => fake()->bankAccountNumber(),
            'payment_method' => fake()->randomElement(['bank_transfer', 'check', 'cash']),
            'status' => fake()->randomElement(['pending', 'paid', 'failed']),
            'notes' => fake()->sentence(),
        ];
    }

    public function payment_method_bank_transfer(): static
    {
        return $this->state(['payment_method' => 'bank_transfer']);
    }

    public function payment_method_check(): static
    {
        return $this->state(['payment_method' => 'check']);
    }

    public function payment_method_cash(): static
    {
        return $this->state(['payment_method' => 'cash']);
    }

    public function status_pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function status_paid(): static
    {
        return $this->state(['status' => 'paid']);
    }

    public function status_failed(): static
    {
        return $this->state(['status' => 'failed']);
    }

}
