<?php

namespace Database\Factories;

use App\Models\Production\CapacityPlan;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Production\WorkCenter;


class CapacityPlanFactory extends Factory
{
    protected $model = CapacityPlan::class;

    public function definition(): array
    {
        return [
            'work_center_id' => WorkCenter::factory(),
            'planned_hours' => fake()->randomFloat(2, 0, 1000),
            'actual_hours' => fake()->randomFloat(2, 0, 1000),
            'available_hours' => fake()->randomFloat(2, 0, 1000),
            'load_percentage' => fake()->randomFloat(2, 0, 100),
            'notes' => fake()->sentence(),
        ];
    }

}
