<?php

namespace Database\Factories;

use App\Models\Pricing\ConditionGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConditionGroupFactory extends Factory
{
    protected $model = ConditionGroup::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'operator' => fake()->randomElement(['AND', 'OR']),
        ];
    }

    public function operator_AND(): static
    {
        return $this->state(['operator' => 'AND']);
    }

    public function operator_OR(): static
    {
        return $this->state(['operator' => 'OR']);
    }

}
