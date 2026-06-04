<?php

namespace Database\Factories;

use App\Models\Accounting\Budget;
use App\Models\Accounting\CostCenter;
use App\Models\Accounting\FiscalYear;
use App\Models\Accounting\ProfitCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition(): array
    {
        return [
            'fiscal_year_id' => FiscalYear::factory(),
            'profit_center_id' => ProfitCenter::factory(),
            'cost_center_id' => CostCenter::factory(),
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(['draft', 'active', 'locked', 'closed']),
        ];
    }

    public function status_draft(): static
    {
        return $this->state(['status' => 'draft']);
    }

    public function status_active(): static
    {
        return $this->state(['status' => 'active']);
    }

    public function status_locked(): static
    {
        return $this->state(['status' => 'locked']);
    }

    public function status_closed(): static
    {
        return $this->state(['status' => 'closed']);
    }

}
