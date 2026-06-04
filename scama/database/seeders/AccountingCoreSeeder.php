<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountingCoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('currencies')->insert([
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'decimal_places' => 2,
                'is_base' => true,
                'is_active' => true,
            ],
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => 'EUR',
                'decimal_places' => 2,
                'is_base' => false,
                'is_active' => true,
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol' => 'GBP',
                'decimal_places' => 2,
                'is_base' => false,
                'is_active' => true,
            ],
            [
                'code' => 'BDT',
                'name' => 'Bangladeshi Taka',
                'symbol' => 'Tk',
                'decimal_places' => 2,
                'is_base' => false,
                'is_active' => true,
            ],
        ]);

        DB::table('exchange_rates')->insert([
            [
                'from_currency_id' => 1,
                'to_currency_id' => 2,
                'rate' => 0.92,
                'date' => '2026-01-01',
            ],
            [
                'from_currency_id' => 1,
                'to_currency_id' => 3,
                'rate' => 0.79,
                'date' => '2026-01-01',
            ],
            [
                'from_currency_id' => 2,
                'to_currency_id' => 1,
                'rate' => 1.09,
                'date' => '2026-01-01',
            ],
        ]);

        DB::table('fiscal_years')->insert([
            [
                'name' => 'FY 2026',
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'is_closed' => false,
            ],
            [
                'name' => 'FY 2025',
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-31',
                'is_closed' => true,
            ],
        ]);

        DB::table('account_periods')->insert([
            ['fiscal_year_id' => 1, 'type' => 'month', 'start_date' => '2026-01-01', 'end_date' => '2026-01-31', 'is_closed' => false],
            ['fiscal_year_id' => 1, 'type' => 'month', 'start_date' => '2026-02-01', 'end_date' => '2026-02-28', 'is_closed' => false],
            ['fiscal_year_id' => 1, 'type' => 'month', 'start_date' => '2026-03-01', 'end_date' => '2026-03-31', 'is_closed' => false],
            ['fiscal_year_id' => 1, 'type' => 'month', 'start_date' => '2026-04-01', 'end_date' => '2026-04-30', 'is_closed' => false],
            ['fiscal_year_id' => 1, 'type' => 'month', 'start_date' => '2026-05-01', 'end_date' => '2026-05-31', 'is_closed' => false],
            ['fiscal_year_id' => 1, 'type' => 'quarter', 'start_date' => '2026-01-01', 'end_date' => '2026-03-31', 'is_closed' => false],
            ['fiscal_year_id' => 1, 'type' => 'year', 'start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'is_closed' => false],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-01-01', 'end_date' => '2025-01-31', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-02-01', 'end_date' => '2025-02-28', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-03-01', 'end_date' => '2025-03-31', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-04-01', 'end_date' => '2025-04-30', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-05-01', 'end_date' => '2025-05-31', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-06-01', 'end_date' => '2025-06-30', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-07-01', 'end_date' => '2025-07-31', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-08-01', 'end_date' => '2025-08-31', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-09-01', 'end_date' => '2025-09-30', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-10-01', 'end_date' => '2025-10-31', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-11-01', 'end_date' => '2025-11-30', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'month', 'start_date' => '2025-12-01', 'end_date' => '2025-12-31', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'quarter', 'start_date' => '2025-01-01', 'end_date' => '2025-03-31', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'quarter', 'start_date' => '2025-04-01', 'end_date' => '2025-06-30', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'quarter', 'start_date' => '2025-07-01', 'end_date' => '2025-09-30', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'quarter', 'start_date' => '2025-10-01', 'end_date' => '2025-12-31', 'is_closed' => true],
            ['fiscal_year_id' => 2, 'type' => 'year', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_closed' => true],
        ]);

        DB::table('chart_of_accounts')->insert([
            ['parent_id' => null, 'account_code' => '1000', 'account_name' => 'Cash & Bank', 'type' => 'asset', 'subtype' => 'current_asset', 'is_active' => true, 'description' => 'Cash in hand and bank accounts'],
            ['parent_id' => null, 'account_code' => '2000', 'account_name' => 'Accounts Payable', 'type' => 'liability', 'subtype' => 'current', 'is_active' => true, 'description' => 'Money owed to suppliers'],
            ['parent_id' => null, 'account_code' => '3000', 'account_name' => 'Retained Earnings', 'type' => 'equity', 'subtype' => 'retained', 'is_active' => true, 'description' => 'Accumulated retained earnings'],
            ['parent_id' => null, 'account_code' => '4000', 'account_name' => 'Sales Revenue', 'type' => 'revenue', 'subtype' => 'sales', 'is_active' => true, 'description' => 'Revenue from product sales'],
            ['parent_id' => null, 'account_code' => '5000', 'account_name' => 'Cost of Goods Sold', 'type' => 'expense', 'subtype' => 'cogs', 'is_active' => true, 'description' => 'Direct costs of goods sold'],
            ['parent_id' => 1, 'account_code' => '1100', 'account_name' => 'Operating Account', 'type' => 'asset', 'subtype' => 'bank', 'is_active' => true, 'description' => 'Main operating checking account'],
            ['parent_id' => 2, 'account_code' => '2100', 'account_name' => 'Supplier Invoices', 'type' => 'liability', 'subtype' => 'trade_payable', 'is_active' => true, 'description' => 'Unpaid supplier invoices'],
        ]);

        DB::table('cost_centers')->insert([
            ['code' => 'CC-ADMIN', 'name' => 'Administration', 'description' => 'General administrative overhead', 'is_active' => true],
            ['code' => 'CC-SALES', 'name' => 'Sales & Marketing', 'description' => 'Sales team and marketing campaigns', 'is_active' => true],
            ['code' => 'CC-DEV', 'name' => 'Development', 'description' => 'Software development team', 'is_active' => true],
        ]);

        DB::table('profit_centers')->insert([
            ['code' => 'PC-SAAS', 'name' => 'SaaS Products', 'description' => 'Subscription-based SaaS products', 'is_active' => true],
            ['code' => 'PC-APPS', 'name' => 'Desktop Apps', 'description' => 'One-time desktop application sales', 'is_active' => true],
        ]);

        DB::table('journal_entry_types')->insert([
            ['name' => 'Sales Invoice', 'code' => 'SALES', 'description' => 'Revenue from customer sales'],
            ['name' => 'Purchase Invoice', 'code' => 'PURCHASE', 'description' => 'Supplier purchase invoices'],
            ['name' => 'Journal Voucher', 'code' => 'JOURNAL', 'description' => 'General journal adjustments'],
        ]);
    }
}
