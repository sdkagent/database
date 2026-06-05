<?php

namespace Database\Factories;

use App\Models\Hr\Timesheet;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;
use App\Models\Hr\Employee;


class TimesheetFactory extends Factory
{
    protected $model = Timesheet::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'date' => fake()->date(),
            'start_time' => fake()->randomFloat(2, 0, 1000),
            'end_time' => fake()->randomFloat(2, 0, 1000),
            'total_hours' => fake()->randomFloat(2, 0, 1000),
            'break_hours' => fake()->randomFloat(2, 0, 1000),
            'description' => fake()->sentence(),
            'is_approved' => fake()->boolean(),
            'approved_by' => User::factory(),
        ];
    }

}
