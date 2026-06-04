<?php

namespace Database\Factories;

use App\Models\Hr\JobPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobPositionFactory extends Factory
{
    protected $model = JobPosition::class;

    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->sentence(),
            'requirements' => fake()->sentence(),
            'salary_min' => fake()->randomFloat(2, 0, 1000),
            'salary_max' => fake()->randomFloat(2, 0, 1000),
            'is_active' => fake()->boolean(),
        ];
    }

}
