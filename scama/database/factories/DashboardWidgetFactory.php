<?php

namespace Database\Factories;

use App\Models\Reporting\DashboardWidget;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DashboardWidgetFactory extends Factory
{
    protected $model = DashboardWidget::class;

    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'dashboard_name' => fake()->randomElement(['default', 'analytics', 'sales']),
            'widget_type'    => fake()->randomElement(['chart', 'table', 'metric', 'list']),
            'title'          => fake()->words(3, true),
            'config'         => ['refresh_interval' => 30],
            'position_x'     => fake()->numberBetween(0, 12),
            'position_y'     => fake()->numberBetween(0, 10),
            'width'          => fake()->numberBetween(2, 6),
            'height'         => fake()->numberBetween(2, 4),
        ];
    }

    public function dashboard_name_analytics(): static
    {
        return $this->state(['dashboard_name' => 'analytics']);
    }

    public function dashboard_name_default(): static
    {
        return $this->state(['dashboard_name' => 'default']);
    }

    public function dashboard_name_sales(): static
    {
        return $this->state(['dashboard_name' => 'sales']);
    }

    public function widget_type_chart(): static
    {
        return $this->state(['widget_type' => 'chart']);
    }

    public function widget_type_list(): static
    {
        return $this->state(['widget_type' => 'list']);
    }

    public function widget_type_metric(): static
    {
        return $this->state(['widget_type' => 'metric']);
    }

    public function widget_type_table(): static
    {
        return $this->state(['widget_type' => 'table']);
    }
}
