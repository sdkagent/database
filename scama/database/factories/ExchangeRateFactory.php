<?php

namespace Database\Factories;

use App\Models\Billing\ExchangeRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExchangeRateFactory extends Factory
{
    protected $model = ExchangeRate::class;

    public function definition(): array
    {
        return [
            'from_currency_id' => Currency::factory(),
            'to_currency_id' => Currency::factory(),
            'rate' => fake()->randomFloat(2, 0, 1000),
            'date' => fake()->date(),
        ];
    }

}
