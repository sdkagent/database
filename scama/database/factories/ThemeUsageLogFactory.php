<?php

namespace Database\Factories;

use App\Models\Theme\ThemeUsageLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeUsageLogFactory extends Factory
{
    protected $model = ThemeUsageLog::class;

    public function definition(): array
    {
        return [
            'theme_id'   => \App\Models\Theme::factory(),
            'user_id'    => \App\Models\User::factory(),
            'action'     => fake()->randomElement(['activated', 'deactivated', 'customized']),
            'ip_address' => fake()->ipv4(),
        ];
    }

    public function action_activated(): static
    {
        return $this->state(['action' => 'activated']);
    }

    public function action_deactivated(): static
    {
        return $this->state(['action' => 'deactivated']);
    }

    public function action_customized(): static
    {
        return $this->state(['action' => 'customized']);
    }
}
