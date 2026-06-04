<?php

namespace Database\Factories;

use App\Models\Accounting\CostCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostCenterFactory extends Factory
{
    protected $model = CostCenter::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('??-####'),
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'is_active' => fake()->boolean(),
        ];
    }

}
