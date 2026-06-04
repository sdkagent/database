<?php

namespace Database\Factories;

use App\Models\Accounting\ProfitCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfitCenterFactory extends Factory
{
    protected $model = ProfitCenter::class;

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
