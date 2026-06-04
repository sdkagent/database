<?php

namespace Database\Factories;

use App\Models\Accounting\FiscalYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class FiscalYearFactory extends Factory
{
    protected $model = FiscalYear::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'is_closed' => fake()->boolean(),
        ];
    }

}
