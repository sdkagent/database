<?php

namespace Database\Factories;

use App\Models\Form\RoutingStep;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Form\Routing;
use App\Models\Production\WorkCenter;


class RoutingStepFactory extends Factory
{
    protected $model = RoutingStep::class;

    public function definition(): array
    {
        return [
            'routing_id' => Routing::factory(),
            'work_center_id' => WorkCenter::factory(),
            'step_name' => fake()->words(2, true),
            'step_order' => fake()->numberBetween(1, 20),
            'setup_time' => fake()->randomFloat(2, 0, 1000),
            'run_time' => fake()->randomFloat(2, 0, 1000),
            'teardown_time' => fake()->randomFloat(2, 0, 1000),
            'notes' => fake()->sentence(),
        ];
    }

}
