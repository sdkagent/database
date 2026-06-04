<?php

namespace Database\Factories;

use App\Models\Accounting\ChartOfAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChartOfAccountFactory extends Factory
{
    protected $model = ChartOfAccount::class;

    public function definition(): array
    {
        return [
            'parent_id' => ChartOfAccount::factory(),
            'account_code' => fake()->unique()->bothify('####'),
            'account_name' => fake()->words(3, true),
            'type' => fake()->randomElement(['asset', 'liability', 'equity', 'revenue', 'expense']),
            'subtype' => fake()->randomElement(['current_asset', 'fixed_asset', 'current_liability', 'long_term_liability', 'operating_revenue', 'operating_expense']),
            'is_active' => fake()->boolean(),
            'is_control' => fake()->boolean(),
            'description' => fake()->sentence(),
        ];
    }

    public function type_asset(): static
    {
        return $this->state(['type' => 'asset']);
    }

    public function type_liability(): static
    {
        return $this->state(['type' => 'liability']);
    }

    public function type_equity(): static
    {
        return $this->state(['type' => 'equity']);
    }

    public function type_revenue(): static
    {
        return $this->state(['type' => 'revenue']);
    }

    public function type_expense(): static
    {
        return $this->state(['type' => 'expense']);
    }

}
