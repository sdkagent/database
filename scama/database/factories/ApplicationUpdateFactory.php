<?php

namespace Database\Factories;

use App\Models\System\ApplicationUpdate;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationUpdateFactory extends Factory
{
    protected $model = ApplicationUpdate::class;

    public function definition(): array
    {
        return [
            'version'  => fake()->semver(),
            'type'     => fake()->randomElement(['update', 'version_history']),
            'changelog' => fake()->paragraphs(2, true),
        ];
    }

    public function update(): static
    {
        return $this->state(fn(array $attrs) => ['type' => 'update']);
    }

    public function versionHistory(): static
    {
        return $this->state(fn(array $attrs) => ['type' => 'version_history']);
    }
}
