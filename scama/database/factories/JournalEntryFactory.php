<?php

namespace Database\Factories;

use App\Models\Accounting\JournalEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalEntryFactory extends Factory
{
    protected $model = JournalEntry::class;

    public function definition(): array
    {
        return [
            'entry_number' => fake()->unique()->bothify('JE-########'),
            'entry_type_id' => JournalEntryType::factory(),
            'fiscal_year_id' => FiscalYear::factory(),
            'account_period_id' => AccountPeriod::factory(),
            'description' => fake()->sentence(),
            'reference_type' => fake()->randomElement(['invoice', 'payment', 'purchase_order', 'manual']),
            'reference_id' => fake()->numberBetween(1, 1000),
            'created_by' => User::factory(),
            'is_posted' => fake()->boolean(),
        ];
    }

}
