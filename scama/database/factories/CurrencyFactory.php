<?php

namespace Database\Factories;

use App\Models\Billing\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('??-####'),
            'name' => fake()->name(),
            'symbol' => fake()->randomElement(['$', '€', '£', '¥', '₩']),
            'decimal_places' => fake()->numberBetween(0, 100),
            'is_base' => fake()->boolean(),
            'is_active' => fake()->boolean(),
        ];
    }

}
