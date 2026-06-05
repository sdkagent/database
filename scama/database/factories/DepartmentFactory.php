<?php

namespace Database\Factories;

use App\Models\Hr\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;


class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'parent_id' => Department::factory(),
            'code' => fake()->unique()->bothify('??-####'),
            'name' => fake()->name(),
            'manager_id' => User::factory(),
            'is_active' => fake()->boolean(),
        ];
    }

}
