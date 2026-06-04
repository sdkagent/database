<?php

namespace Database\Factories;

use App\Models\Tax\TaxRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxRateFactory extends Factory
{
    protected $model = TaxRate::class;

    public function definition(): array
    {
        return [
            'name'       => fake()->randomElement(['Standard', 'Reduced', 'Super Reduced', 'Parking']),
            'rate'       => fake()->randomFloat(2, 0, 25),
            'type'       => fake()->randomElement(['percentage', 'fixed']),
            'country'    => fake()->optional()->countryCode(),
            'region'     => fake()->optional()->state(),
            'is_default' => fake()->boolean(20),
            'active'     => fake()->boolean(80),
        ];
    }

    public function percentage(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'percentage',
        ]);
    }

    public function fixed(): static
    {
        return $this->state(fn(array $attrs) => [
            'type' => 'fixed',
        ]);
    }

    public function active(): static
    {
        return $this->state(fn(array $attrs) => [
            'active' => true,
        ]);
    }

    public function default(): static
    {
        return $this->state(fn(array $attrs) => [
            'is_default' => true,
            'active'     => true,
        ]);
    }
}
