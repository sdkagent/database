<?php

namespace Database\Factories;

use App\Models\Theme\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ThemeFactory extends Factory
{
    protected $model = Theme::class;

    public function definition(): array
    {
        $name = fake()->words(2, true);
        return [
            'name'        => ucwords($name),
            'slug'        => Str::slug($name),
            'description' => fake()->paragraph(),
            'version'     => fake()->semver(),
            'author'      => fake()->name(),
            'status'      => fake()->randomElement(['active', 'inactive']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'active']);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attrs) => ['status' => 'inactive']);
    }
}
