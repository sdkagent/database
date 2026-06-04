<?php

namespace Database\Factories;

use App\Models\Tax\TaxJurisdiction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxJurisdictionFactory extends Factory
{
    protected $model = TaxJurisdiction::class;

    public function definition(): array
    {
        return [
            'name'           => fake()->unique()->city() . ' Tax',
            'country'        => fake()->countryCode(),
            'state'          => fake()->stateAbbr(),
            'city'           => fake()->city(),
            'postal_code'    => fake()->postcode(),
            'rate'           => fake()->randomFloat(4, 0, 0.15),
            'tax_type'       => fake()->randomElement(['sales', 'vat', 'gst', 'hst']),
            'is_compound'    => fake()->boolean(20),
            'priority'       => fake()->numberBetween(0, 100),
            'status'         => fake()->randomElement(['active', 'inactive']),
            'effective_from' => fake()->date(),
            'effective_to'   => null,
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

    public function tax_type_sales(): static
    {
        return $this->state(['tax_type' => 'sales']);
    }

    public function tax_type_vat(): static
    {
        return $this->state(['tax_type' => 'vat']);
    }

    public function tax_type_gst(): static
    {
        return $this->state(['tax_type' => 'gst']);
    }

    public function tax_type_hst(): static
    {
        return $this->state(['tax_type' => 'hst']);
    }
}
