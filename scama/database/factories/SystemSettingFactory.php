<?php

namespace Database\Factories;

use App\Models\System\SystemSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SystemSettingFactory extends Factory
{
    protected $model = SystemSetting::class;

    public function definition(): array
    {
        return [
            'key'   => fake()->unique()->word(),
            'value' => fake()->sentence(),
            'group' => fake()->randomElement(['general', 'security', 'email', 'api', 'billing']),
        ];
    }

    public function group_general(): static
    {
        return $this->state(['group' => 'general']);
    }

    public function group_security(): static
    {
        return $this->state(['group' => 'security']);
    }

    public function group_email(): static
    {
        return $this->state(['group' => 'email']);
    }

    public function group_api(): static
    {
        return $this->state(['group' => 'api']);
    }

    public function group_billing(): static
    {
        return $this->state(['group' => 'billing']);
    }
}
