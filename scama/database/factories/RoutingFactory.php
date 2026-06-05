<?php

namespace Database\Factories;

use App\Models\Form\Routing;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Production\BillOfMaterial;


class RoutingFactory extends Factory
{
    protected $model = Routing::class;

    public function definition(): array
    {
        return [
            'bom_id' => BillOfMaterial::factory(),
            'name' => fake()->name(),
            'total_time' => fake()->randomFloat(2, 0, 1000),
            'is_active' => fake()->boolean(),
        ];
    }

}
