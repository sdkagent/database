<?php

namespace Database\Factories;

use App\Models\Reporting\ReportCategory;
use App\Models\Reporting\ReportDefinition;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportDefinitionFactory extends Factory
{
    protected $model = ReportDefinition::class;

    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->randomElement(['Monthly Sales', 'User Growth', 'Revenue Report', 'Top Products']),
            'slug'        => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'category_id' => ReportCategory::factory(),
            'report_type' => fake()->randomElement(['tabular', 'chart', 'pivot', 'summary']),
            'config'      => ['filters' => ['date_range' => true]],
            'is_system'   => fake()->boolean(20),
            'created_by'  => User::factory(),
        ];
    }

    public function system(): static
    {
        return $this->state(fn(array $attrs) => ['is_system' => true]);
    }

    public function report_type_tabular(): static
    {
        return $this->state(['report_type' => 'tabular']);
    }

    public function report_type_chart(): static
    {
        return $this->state(['report_type' => 'chart']);
    }

    public function report_type_pivot(): static
    {
        return $this->state(['report_type' => 'pivot']);
    }

    public function report_type_summary(): static
    {
        return $this->state(['report_type' => 'summary']);
    }
}
