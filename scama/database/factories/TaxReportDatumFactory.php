<?php

namespace Database\Factories;

use App\Models\Tax\TaxJurisdiction;
use App\Models\Tax\TaxReportDatum;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxReportDatumFactory extends Factory
{
    protected $model = TaxReportDatum::class;

    public function definition(): array
    {
        return [
            'tax_jurisdiction_id' => TaxJurisdiction::factory(),
            'period_start'        => fake()->date(),
            'period_end'          => fake()->date(),
            'taxable_amount'      => fake()->randomFloat(2, 1000, 100000),
            'tax_collected'       => fake()->randomFloat(2, 50, 15000),
            'returns_filed'       => fake()->boolean(70),
            'filed_at'            => null,
            'notes'               => null,
        ];
    }

    public function filed(): static
    {
        return $this->state(fn(array $attrs) => [
            'returns_filed' => true,
            'filed_at'      => now(),
        ]);
    }

    public function unfiled(): static
    {
        return $this->state(fn(array $attrs) => [
            'returns_filed' => false,
            'filed_at'      => null,
        ]);
    }
}
