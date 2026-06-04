<?php

namespace Database\Factories;

use App\Models\System\FeatureFlag;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeatureFlagFactory extends Factory
{
    protected $model = FeatureFlag::class;

    public function definition(): array
    {
        return [
            'name'        => ucwords(str_replace('_', ' ', $key = fake()->unique()->word())),
            'key'         => $key,
            'description' => fake()->sentence(),
            'enabled'     => fake()->boolean(),
            'conditions'  => null,
        ];
    }

    public function enabled(): static
    {
        return $this->state(fn(array $attrs) => ['enabled' => true]);
    }

    public function disabled(): static
    {
        return $this->state(fn(array $attrs) => ['enabled' => false]);
    }
}
