<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('budgets')->insert([
            [
                'fiscal_year_id' => 1,
                'profit_center_id' => 1,
                'cost_center_id' => 2,
                'name' => 'SaaS Revenue Budget 2026',
                'description' => 'Projected SaaS subscription revenue for FY 2026',
                'status' => 'active',
            ],
            [
                'fiscal_year_id' => 1,
                'profit_center_id' => 2,
                'cost_center_id' => 3,
                'name' => 'Desktop Apps Budget 2026',
                'description' => 'Projected desktop application sales for FY 2026',
                'status' => 'active',
            ],
        ]);

        DB::table('budget_lines')->insert([
            ['budget_id' => 1, 'account_id' => 4, 'period_id' => 1, 'amount' => 50000.00],
            ['budget_id' => 1, 'account_id' => 4, 'period_id' => 2, 'amount' => 52000.00],
            ['budget_id' => 1, 'account_id' => 4, 'period_id' => 3, 'amount' => 48000.00],
            ['budget_id' => 2, 'account_id' => 4, 'period_id' => 1, 'amount' => 25000.00],
            ['budget_id' => 2, 'account_id' => 5, 'period_id' => 1, 'amount' => 10000.00],
        ]);

        DB::table('budget_versions')->insert([
            [
                'budget_id' => 1,
                'version' => 1,
                'notes' => 'Initial budget draft for FY 2026',
                'snapshot' => '{"total_revenue": 50000, "total_expense": 30000}',
                'created_by' => 1,
            ],
            [
                'budget_id' => 1,
                'version' => 2,
                'notes' => 'Revised after Q1 results',
                'snapshot' => '{"total_revenue": 52000, "total_expense": 31000}',
                'created_by' => 1,
            ],
        ]);

        DB::table('cost_allocations')->insert([
            [
                'source_cost_center_id' => 1,
                'target_cost_center_id' => 2,
                'account_id' => 7,
                'allocation_method' => 'percentage',
                'allocation_value' => 30.00,
                'is_active' => true,
            ],
            [
                'source_cost_center_id' => 1,
                'target_cost_center_id' => 3,
                'account_id' => 7,
                'allocation_method' => 'percentage',
                'allocation_value' => 70.00,
                'is_active' => true,
            ],
        ]);
    }
}
