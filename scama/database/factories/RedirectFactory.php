<?php

namespace Database\Factories;

use App\Models\Cms\Redirect;
use Illuminate\Database\Eloquent\Factories\Factory;

class RedirectFactory extends Factory
{
    protected $model = Redirect::class;

    public function definition(): array
    {
        return [
            'old_path'    => '/' . fake()->slug(),
            'new_path'    => '/' . fake()->slug(),
            'status_code' => fake()->randomElement(['301', '302', '307']),
            'is_active'   => true,
            'hits_count'  => fake()->numberBetween(0, 10000),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['is_active' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => ['is_active' => false]);
    }

    public function status_code_301(): static
    {
        return $this->state(['status_code' => '301']);
    }

    public function status_code_302(): static
    {
        return $this->state(['status_code' => '302']);
    }

    public function status_code_307(): static
    {
        return $this->state(['status_code' => '307']);
    }
}
