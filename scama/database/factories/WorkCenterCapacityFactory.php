<?php

namespace Database\Factories;

use App\Models\Production\WorkCenterCapacity;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkCenterCapacityFactory extends Factory
{
    protected $model = WorkCenterCapacity::class;

    public function definition(): array
    {
        return [
            'work_center_id' => WorkCenter::factory(),
            'available_hours' => fake()->randomFloat(2, 0, 1000),
            'maintenance_hours' => fake()->randomFloat(2, 0, 1000),
            'booked_hours' => fake()->randomFloat(2, 0, 1000),
            'overtime_hours' => fake()->randomFloat(2, 0, 1000),
            'notes' => fake()->sentence(),
        ];
    }

}
