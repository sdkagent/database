<?php

namespace Database\Factories;

use App\Models\Hr\LeaveBalance;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hr\Employee;
use App\Models\Hr\LeaveType;


class LeaveBalanceFactory extends Factory
{
    protected $model = LeaveBalance::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'leave_type_id' => LeaveType::factory(),
            'year' => (string) fake()->numberBetween(2020, 2026),
            'total_days' => fake()->numberBetween(0, 100),
            'used_days' => fake()->randomNumber(2),
            'pending_days' => fake()->randomNumber(2),
            'remaining_days' => fake()->randomNumber(2),
        ];
    }

}
