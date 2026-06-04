<?php

namespace Database\Factories;

use App\Models\Accounting\CostAllocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostAllocationFactory extends Factory
{
    protected $model = CostAllocation::class;

    public function definition(): array
    {
        return [
            'source_cost_center_id' => CostCenter::factory(),
            'target_cost_center_id' => CostCenter::factory(),
            'account_id' => ChartOfAccount::factory(),
            'allocation_method' => fake()->randomElement(['percentage', 'fixed', 'activity_based']),
            'allocation_value' => fake()->randomFloat(2, 0, 1000),
            'is_active' => fake()->boolean(),
        ];
    }

    public function allocation_method_percentage(): static
    {
        return $this->state(['allocation_method' => 'percentage']);
    }

    public function allocation_method_fixed(): static
    {
        return $this->state(['allocation_method' => 'fixed']);
    }

    public function allocation_method_activity_based(): static
    {
        return $this->state(['allocation_method' => 'activity_based']);
    }

}
