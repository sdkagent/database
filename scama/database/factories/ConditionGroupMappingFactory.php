<?php

namespace Database\Factories;

use App\Models\Pricing\ConditionGroupMapping;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pricing\ConditionGroup;


class ConditionGroupMappingFactory extends Factory
{
    protected $model = ConditionGroupMapping::class;

    public function definition(): array
    {
        return [
            'group_id' => ConditionGroup::factory(),
            'entity_type' => fake()->randomElement(['product', 'order', 'user', 'category']),
            'entity_id' => fake()->numberBetween(1, 1000),
        ];
    }

}
