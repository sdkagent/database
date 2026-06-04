<?php

namespace Database\Factories;

use App\Models\Hr\PayrollComponent;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollComponentFactory extends Factory
{
    protected $model = PayrollComponent::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => fake()->unique()->bothify('??-####'),
            'type' => fake()->randomElement(['earning', 'deduction', 'employer_contribution']),
            'calculation' => fake()->randomElement(['fixed', 'percentage_of_basic', 'percentage_of_gross', 'formula']),
            'value' => json_encode([]),
            'is_taxable' => fake()->boolean(),
            'is_active' => fake()->boolean(),
        ];
    }

    public function type_earning(): static
    {
        return $this->state(['type' => 'earning']);
    }

    public function type_deduction(): static
    {
        return $this->state(['type' => 'deduction']);
    }

    public function type_employer_contribution(): static
    {
        return $this->state(['type' => 'employer_contribution']);
    }

    public function calculation_fixed(): static
    {
        return $this->state(['calculation' => 'fixed']);
    }

    public function calculation_percentage_of_basic(): static
    {
        return $this->state(['calculation' => 'percentage_of_basic']);
    }

    public function calculation_percentage_of_gross(): static
    {
        return $this->state(['calculation' => 'percentage_of_gross']);
    }

    public function calculation_formula(): static
    {
        return $this->state(['calculation' => 'formula']);
    }

}
