<?php

namespace Database\Factories;

use App\Models\Production\ProductionOrderStep;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionOrderStepFactory extends Factory
{
    protected $model = ProductionOrderStep::class;

    public function definition(): array
    {
        return [
            'production_order_id' => ProductionOrder::factory(),
            'routing_step_id' => RoutingStep::factory(),
            'work_center_id' => WorkCenter::factory(),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed', 'skipped']),
            'actual_setup_time' => fake()->randomFloat(2, 0, 1000),
            'actual_run_time' => fake()->randomFloat(2, 0, 1000),
            'completed_qty' => fake()->numberBetween(0, 100),
            'scrap_qty' => fake()->numberBetween(0, 100),
            'notes' => fake()->sentence(),
        ];
    }

    public function status_pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function status_in_progress(): static
    {
        return $this->state(['status' => 'in_progress']);
    }

    public function status_completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function status_skipped(): static
    {
        return $this->state(['status' => 'skipped']);
    }

}
