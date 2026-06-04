<?php

namespace Database\Factories;

use App\Models\Hr\LeaveType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveTypeFactory extends Factory
{
    protected $model = LeaveType::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => fake()->unique()->bothify('??-####'),
            'days_allowed' => fake()->randomNumber(2),
            'is_paid' => fake()->boolean(),
            'carry_forward' => fake()->boolean(),
            'max_carry_days' => fake()->numberBetween(0, 100),
            'is_active' => fake()->boolean(),
        ];
    }

}
