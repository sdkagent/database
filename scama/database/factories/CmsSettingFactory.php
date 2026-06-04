<?php

namespace Database\Factories;

use App\Models\Cms\CmsSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class CmsSettingFactory extends Factory
{
    protected $model = CmsSetting::class;

    public function definition(): array
    {
        return [
            'key'   => fake()->unique()->word(),
            'value' => fake()->sentence(),
            'group' => fake()->randomElement(['general', 'seo', 'analytics', 'social', 'custom']),
        ];
    }

    public function group_analytics(): static
    {
        return $this->state(['group' => 'analytics']);
    }

    public function group_custom(): static
    {
        return $this->state(['group' => 'custom']);
    }

    public function group_general(): static
    {
        return $this->state(['group' => 'general']);
    }

    public function group_seo(): static
    {
        return $this->state(['group' => 'seo']);
    }

    public function group_social(): static
    {
        return $this->state(['group' => 'social']);
    }
}
