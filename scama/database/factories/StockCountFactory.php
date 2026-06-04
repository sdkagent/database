<?php

namespace Database\Factories;

use App\Models\Inventory\StockCount;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockCountFactory extends Factory
{
    protected $model = StockCount::class;

    public function definition(): array
    {
        return [
            'warehouse_id' => Warehouse::factory(),
            'status' => fake()->randomElement(['planned', 'in_progress', 'completed', 'verified']),
            'counted_by' => User::factory(),
            'verified_by' => User::factory(),
            'notes' => fake()->sentence(),
        ];
    }

    public function status_planned(): static
    {
        return $this->state(['status' => 'planned']);
    }

    public function status_in_progress(): static
    {
        return $this->state(['status' => 'in_progress']);
    }

    public function status_completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function status_verified(): static
    {
        return $this->state(['status' => 'verified']);
    }

}
