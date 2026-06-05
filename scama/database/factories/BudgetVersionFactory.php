<?php

namespace Database\Factories;

use App\Models\Accounting\BudgetVersion;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Accounting\Budget;
use App\Models\Auth\User;


class BudgetVersionFactory extends Factory
{
    protected $model = BudgetVersion::class;

    public function definition(): array
    {
        return [
            'budget_id' => Budget::factory(),
            'version' => fake()->numberBetween(0, 100),
            'notes' => fake()->sentence(),
            'snapshot' => json_encode([]),
            'created_by' => User::factory(),
        ];
    }

}
