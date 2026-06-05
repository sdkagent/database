<?php

namespace Database\Factories;

use App\Models\Accounting\JournalEntryLine;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Accounting\ChartOfAccount;
use App\Models\Accounting\CostCenter;
use App\Models\Accounting\JournalEntry;
use App\Models\Accounting\ProfitCenter;


class JournalEntryLineFactory extends Factory
{
    protected $model = JournalEntryLine::class;

    public function definition(): array
    {
        return [
            'journal_entry_id' => JournalEntry::factory(),
            'account_id' => ChartOfAccount::factory(),
            'cost_center_id' => CostCenter::factory(),
            'profit_center_id' => ProfitCenter::factory(),
            'debit' => fake()->randomFloat(2, 0, 10000),
            'credit' => fake()->randomFloat(2, 0, 10000),
            'description' => fake()->sentence(),
            'line_order' => fake()->numberBetween(0, 100),
        ];
    }

}
