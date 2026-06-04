<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayrollTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payroll_components')->insert([
            ['name' => 'Basic Salary', 'code' => 'BASIC', 'type' => 'earning', 'calculation' => 'fixed', 'value' => 8000.00, 'is_taxable' => true, 'is_active' => true],
            ['name' => 'Housing Allowance', 'code' => 'HOUSING', 'type' => 'earning', 'calculation' => 'percentage_of_basic', 'value' => 20.00, 'is_taxable' => true, 'is_active' => true],
            ['name' => 'Health Insurance', 'code' => 'HEALTH', 'type' => 'deduction', 'calculation' => 'fixed', 'value' => 500.00, 'is_taxable' => true, 'is_active' => true],
            ['name' => 'Tax Withholding', 'code' => 'TAX', 'type' => 'deduction', 'calculation' => 'percentage_of_gross', 'value' => 15.00, 'is_taxable' => true, 'is_active' => true],
            ['name' => 'Employer Pension', 'code' => 'PENSION', 'type' => 'employer_contribution', 'calculation' => 'percentage_of_basic', 'value' => 10.00, 'is_taxable' => false, 'is_active' => true],
        ]);

        DB::table('payroll_runs')->insert([
            [
                'fiscal_year_id' => 1,
                'account_period_id' => 1,
                'run_number' => 'PR-2026-05-001',
                'period_start' => '2026-05-01',
                'period_end' => '2026-05-31',
                'payment_date' => '2026-05-31',
                'status' => 'completed',
                'total_gross' => 38000.00,
                'total_deductions' => 5700.00,
                'total_net' => 32300.00,
                'notes' => 'May 2026 payroll run',
                'processed_by' => 1,
            ],
            [
                'fiscal_year_id' => 1,
                'account_period_id' => 2,
                'run_number' => 'PR-2026-04-001',
                'period_start' => '2026-04-01',
                'period_end' => '2026-04-30',
                'payment_date' => '2026-04-30',
                'status' => 'completed',
                'total_gross' => 38000.00,
                'total_deductions' => 5700.00,
                'total_net' => 32300.00,
                'notes' => 'April 2026 payroll run',
                'processed_by' => 1,
            ],
        ]);

        DB::table('payroll_items')->insert([
            ['payroll_run_id' => 1, 'employee_id' => 1, 'gross_pay' => 15000.00, 'total_deductions' => 2250.00, 'net_pay' => 12750.00, 'bank_account' => '****1234', 'payment_method' => 'bank_transfer', 'status' => 'paid'],
            ['payroll_run_id' => 1, 'employee_id' => 2, 'gross_pay' => 12000.00, 'total_deductions' => 1800.00, 'net_pay' => 10200.00, 'bank_account' => '****5678', 'payment_method' => 'bank_transfer', 'status' => 'paid'],
            ['payroll_run_id' => 1, 'employee_id' => 3, 'gross_pay' => 11000.00, 'total_deductions' => 1650.00, 'net_pay' => 9350.00, 'bank_account' => '****9012', 'payment_method' => 'bank_transfer', 'status' => 'paid'],
        ]);

        DB::table('payroll_item_details')->insert([
            ['payroll_item_id' => 1, 'payroll_component_id' => 1, 'amount' => 8000.00],
            ['payroll_item_id' => 1, 'payroll_component_id' => 2, 'amount' => 1600.00],
            ['payroll_item_id' => 1, 'payroll_component_id' => 3, 'amount' => 500.00],
            ['payroll_item_id' => 1, 'payroll_component_id' => 4, 'amount' => 2250.00],
            ['payroll_item_id' => 2, 'payroll_component_id' => 1, 'amount' => 8000.00],
            ['payroll_item_id' => 2, 'payroll_component_id' => 2, 'amount' => 1600.00],
            ['payroll_item_id' => 3, 'payroll_component_id' => 3, 'amount' => 500.00],
        ]);
    }
}
