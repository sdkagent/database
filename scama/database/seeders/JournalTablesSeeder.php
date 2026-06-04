<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JournalTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('journal_entries')->insert([
            [
                'entry_number' => 'JE-2026-0001',
                'entry_type_id' => 1,
                'fiscal_year_id' => 1,
                'account_period_id' => 1,
                'entry_date' => '2026-05-01',
                'description' => 'Sale of Ultimate CRM Script',
                'reference_type' => 'order',
                'reference_id' => 1,
                'created_by' => 1,
                'is_posted' => true,
                'posted_at' => '2026-05-01T00:10:00',
            ],
            [
                'entry_number' => 'JE-2026-0002',
                'entry_type_id' => 2,
                'fiscal_year_id' => 1,
                'account_period_id' => 5,
                'entry_date' => '2026-05-05',
                'description' => 'Server hosting invoice #INV-2026-001',
                'reference_type' => 'order',
                'reference_id' => 2,
                'created_by' => 1,
                'is_posted' => true,
                'posted_at' => '2026-05-05T12:00:00',
            ],
            [
                'entry_number' => 'JE-2026-0003',
                'entry_type_id' => 3,
                'fiscal_year_id' => 1,
                'account_period_id' => 5,
                'entry_date' => '2026-05-15',
                'description' => 'Monthly accrual adjustment',
                'reference_type' => null,
                'reference_id' => null,
                'created_by' => 1,
                'is_posted' => true,
                'posted_at' => '2026-05-15T23:59:00',
            ],
        ]);

        DB::table('journal_entry_lines')->insert([
            ['journal_entry_id' => 1, 'account_id' => 6, 'cost_center_id' => 1, 'profit_center_id' => 1, 'debit' => 328.90, 'credit' => 0.00, 'description' => 'Cash received from customer', 'line_order' => 1],
            ['journal_entry_id' => 1, 'account_id' => 4, 'cost_center_id' => 1, 'profit_center_id' => 1, 'debit' => 0.00, 'credit' => 328.90, 'description' => 'Sales revenue recognized', 'line_order' => 2],
            ['journal_entry_id' => 2, 'account_id' => 7, 'cost_center_id' => 1, 'profit_center_id' => 1, 'debit' => 1200.00, 'credit' => 0.00, 'description' => 'Server hosting expense', 'line_order' => 1],
            ['journal_entry_id' => 2, 'account_id' => 2, 'cost_center_id' => 1, 'profit_center_id' => 1, 'debit' => 0.00, 'credit' => 1200.00, 'description' => 'Accounts payable - CloudHost Inc', 'line_order' => 2],
            ['journal_entry_id' => 3, 'account_id' => 5, 'cost_center_id' => 2, 'profit_center_id' => 1, 'debit' => 500.00, 'credit' => 0.00, 'description' => 'Accrued COGS adjustment', 'line_order' => 1],
            ['journal_entry_id' => 3, 'account_id' => 4, 'cost_center_id' => 2, 'profit_center_id' => 1, 'debit' => 0.00, 'credit' => 500.00, 'description' => 'Revenue adjustment', 'line_order' => 2],
        ]);

        DB::table('account_balances')->insert([
            ['account_id' => 6, 'fiscal_year_id' => 1, 'account_period_id' => 1, 'period_type' => 'month', 'opening_balance' => 15000.00, 'period_debit' => 5000.00, 'period_credit' => 2000.00, 'closing_balance' => 18000.00],
            ['account_id' => 4, 'fiscal_year_id' => 1, 'account_period_id' => 1, 'period_type' => 'month', 'opening_balance' => 0.00, 'period_debit' => 0.00, 'period_credit' => 5000.00, 'closing_balance' => 5000.00],
            ['account_id' => 7, 'fiscal_year_id' => 1, 'account_period_id' => 1, 'period_type' => 'month', 'opening_balance' => 2000.00, 'period_debit' => 1200.00, 'period_credit' => 800.00, 'closing_balance' => 2400.00],
        ]);
    }
}
