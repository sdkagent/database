<?php

namespace Database\Factories;

use App\Models\Tax\TaxRule;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxRuleFactory extends Factory
{
    protected $model = TaxRule::class;

    public function definition(): array
    {
        return [
            'name'         => fake()->randomElement(['Standard VAT', 'Reduced Rate', 'Zero Rate', 'Exempt']),
            'priority'     => fake()->numberBetween(0, 100),
            'conditions'   => ['country' => fake()->countryCode()],
            'action_type'  => fake()->randomElement(['rate_override', 'exempt', 'compound', 'reduce']),
            'action_value' => ['rate' => fake()->randomFloat(4, 0, 0.10)],
            'status'       => fake()->randomElement(['active', 'inactive']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'active']);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'inactive']);
    }

    public function action_type_rate_override(): static
    {
        return $this->state(['action_type' => 'rate_override']);
    }

    public function action_type_exempt(): static
    {
        return $this->state(['action_type' => 'exempt']);
    }

    public function action_type_compound(): static
    {
        return $this->state(['action_type' => 'compound']);
    }

    public function action_type_reduce(): static
    {
        return $this->state(['action_type' => 'reduce']);
    }
}
