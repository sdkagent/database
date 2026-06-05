<?php

namespace Database\Factories;

use App\Models\Production\ProductionOrder;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Auth\User;
use App\Models\Form\Routing;
use App\Models\Inventory\Warehouse;
use App\Models\Product\Product;
use App\Models\Production\BillOfMaterial;


class ProductionOrderFactory extends Factory
{
    protected $model = ProductionOrder::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'bom_id' => BillOfMaterial::factory(),
            'routing_id' => Routing::factory(),
            'warehouse_id' => Warehouse::factory(),
            'order_number' => fake()->unique()->bothify('MO-########'),
            'quantity' => fake()->randomFloat(2, 0, 1000),
            'produced_qty' => fake()->numberBetween(0, 100),
            'scrap_qty' => fake()->numberBetween(0, 100),
            'status' => fake()->randomElement(['planned', 'released', 'in_progress', 'completed', 'cancelled', 'on_hold']),
            'priority' => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'scheduled_start' => fake()->dateTimeBetween('now', '+1 week'),
            'scheduled_end' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'actual_start' => fake()->optional()->dateTimeBetween('-1 week', 'now'),
            'actual_end' => fake()->optional()->dateTimeBetween('now', '+1 week'),
            'notes' => fake()->sentence(),
            'created_by' => User::factory(),
        ];
    }

    public function status_planned(): static
    {
        return $this->state(['status' => 'planned']);
    }

    public function status_released(): static
    {
        return $this->state(['status' => 'released']);
    }

    public function status_in_progress(): static
    {
        return $this->state(['status' => 'in_progress']);
    }

    public function status_completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function status_cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

    public function status_on_hold(): static
    {
        return $this->state(['status' => 'on_hold']);
    }

    public function priority_low(): static
    {
        return $this->state(['priority' => 'low']);
    }

    public function priority_medium(): static
    {
        return $this->state(['priority' => 'medium']);
    }

    public function priority_high(): static
    {
        return $this->state(['priority' => 'high']);
    }

    public function priority_urgent(): static
    {
        return $this->state(['priority' => 'urgent']);
    }

}
