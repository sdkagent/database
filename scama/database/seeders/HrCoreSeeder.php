<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrCoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departments')->insert([
            ['parent_id' => null, 'code' => 'DEPT-EXEC', 'name' => 'Executive', 'manager_id' => 1, 'is_active' => true],
            ['parent_id' => null, 'code' => 'DEPT-ENG', 'name' => 'Engineering', 'manager_id' => 1, 'is_active' => true],
            ['parent_id' => 1, 'code' => 'DEPT-SALES', 'name' => 'Sales & Marketing', 'manager_id' => 2, 'is_active' => true],
            ['parent_id' => 2, 'code' => 'DEPT-DEV', 'name' => 'Software Development', 'manager_id' => 1, 'is_active' => true],
        ]);

        DB::table('job_positions')->insert([
            ['department_id' => 1, 'title' => 'Chief Technology Officer', 'description' => 'Oversees all technology and engineering', 'requirements' => '15+ years in software, 5+ in leadership', 'salary_min' => 150000.00, 'salary_max' => 250000.00, 'is_active' => true],
            ['department_id' => 2, 'title' => 'Senior Software Engineer', 'description' => 'Develops and maintains core platform', 'requirements' => '8+ years in PHP/Laravel, 3+ in SaaS', 'salary_min' => 100000.00, 'salary_max' => 160000.00, 'is_active' => true],
            ['department_id' => 3, 'title' => 'Sales Manager', 'description' => 'Leads sales team and drives revenue growth', 'requirements' => '10+ years B2B SaaS sales experience', 'salary_min' => 90000.00, 'salary_max' => 150000.00, 'is_active' => true],
        ]);

        DB::table('employees')->insert([
            ['user_id' => 1, 'employee_number' => 'EMP-0001', 'department_id' => 1, 'job_position_id' => 1, 'reports_to' => null, 'hire_date' => '2023-01-15', 'termination_date' => null, 'employment_type' => 'full_time', 'status' => 'active', 'base_salary' => 180000.00, 'currency_id' => 1, 'emergency_contact' => '{"name": "Jane Doe", "phone": "+1-555-9999", "relation": "spouse"}'],
            ['user_id' => 2, 'employee_number' => 'EMP-0002', 'department_id' => 3, 'job_position_id' => 3, 'reports_to' => 1, 'hire_date' => '2024-06-01', 'termination_date' => null, 'employment_type' => 'full_time', 'status' => 'active', 'base_salary' => 120000.00, 'currency_id' => 1, 'emergency_contact' => '{"name": "Mark Dev", "phone": "+1-555-8888", "relation": "brother"}'],
            ['user_id' => 3, 'employee_number' => 'EMP-0003', 'department_id' => 2, 'job_position_id' => 2, 'reports_to' => 1, 'hire_date' => '2025-03-10', 'termination_date' => null, 'employment_type' => 'full_time', 'status' => 'active', 'base_salary' => 140000.00, 'currency_id' => 1, 'emergency_contact' => '{"name": "Sarah Buyer", "phone": "+1-555-7777", "relation": "sister"}'],
            ['user_id' => 4, 'employee_number' => 'EMP-0004', 'department_id' => 2, 'job_position_id' => 2, 'reports_to' => 3, 'hire_date' => '2025-03-15', 'termination_date' => null, 'employment_type' => 'full_time', 'status' => 'active', 'base_salary' => 95000.00, 'currency_id' => 1, 'emergency_contact' => '{"name": "Tom Support", "phone": "+1-555-6666", "relation": "friend"}'],
        ]);

        DB::table('employee_contracts')->insert([
            ['employee_id' => 1, 'contract_type' => 'permanent', 'start_date' => '2023-01-15', 'end_date' => null, 'salary' => 180000.00, 'currency_id' => 1, 'benefits' => '{"health_insurance": true, "stock_options": 5000}', 'status' => 'active'],
            ['employee_id' => 2, 'contract_type' => 'permanent', 'start_date' => '2024-06-01', 'end_date' => null, 'salary' => 120000.00, 'currency_id' => 1, 'benefits' => '{"health_insurance": true, "stock_options": 2000}', 'status' => 'active'],
            ['employee_id' => 3, 'contract_type' => 'permanent', 'start_date' => '2025-03-10', 'end_date' => null, 'salary' => 140000.00, 'currency_id' => 1, 'benefits' => '{"health_insurance": true}', 'status' => 'active'],
        ]);

        DB::table('employee_documents')->insert([
            ['employee_id' => 1, 'document_type' => 'id', 'file_name' => 'john_doe_passport.pdf', 'file_path' => '/hr/documents/emp-0001/passport.pdf', 'expiry_date' => '2031-01-15', 'is_verified' => true, 'notes' => 'US Passport - verified'],
            ['employee_id' => 2, 'document_type' => 'contract', 'file_name' => 'mark_dev_contract.pdf', 'file_path' => '/hr/documents/emp-0002/contract.pdf', 'expiry_date' => null, 'is_verified' => true, 'notes' => 'Signed employment contract'],
            ['employee_id' => 3, 'document_type' => 'certificate', 'file_name' => 'sarah_cert.pdf', 'file_path' => '/hr/documents/emp-0003/cert.pdf', 'expiry_date' => null, 'is_verified' => false, 'notes' => 'Pending verification of degree'],
        ]);

        DB::table('attendance')->insert([
            ['employee_id' => 1, 'date' => '2026-05-01', 'clock_in' => '2026-05-01T08:00:00', 'clock_out' => '2026-05-01T17:00:00', 'status' => 'present', 'notes' => null],
            ['employee_id' => 1, 'date' => '2026-05-02', 'clock_in' => '2026-05-02T08:30:00', 'clock_out' => '2026-05-02T17:30:00', 'status' => 'present', 'notes' => 'Late by 30 min due to traffic'],
            ['employee_id' => 2, 'date' => '2026-05-01', 'clock_in' => '2026-05-01T09:00:00', 'clock_out' => '2026-05-01T18:00:00', 'status' => 'present', 'notes' => null],
            ['employee_id' => 2, 'date' => '2026-05-02', 'clock_in' => null, 'clock_out' => null, 'status' => 'absent', 'notes' => 'Sick leave - no clock in'],
        ]);

        DB::table('leave_types')->insert([
            ['name' => 'Annual Leave', 'code' => 'ANNUAL', 'days_allowed' => 20, 'is_paid' => true, 'carry_forward' => true, 'max_carry_days' => 5, 'is_active' => true],
            ['name' => 'Sick Leave', 'code' => 'SICK', 'days_allowed' => 10, 'is_paid' => true, 'carry_forward' => false, 'max_carry_days' => 0, 'is_active' => true],
            ['name' => 'Personal Leave', 'code' => 'PERSONAL', 'days_allowed' => 5, 'is_paid' => true, 'carry_forward' => false, 'max_carry_days' => 0, 'is_active' => true],
        ]);

        DB::table('leave_requests')->insert([
            ['employee_id' => 1, 'leave_type_id' => 1, 'start_date' => '2026-07-01', 'end_date' => '2026-07-15', 'reason' => 'Family vacation to Europe', 'status' => 'approved', 'approved_by' => 1, 'approved_at' => '2026-05-15T09:00:00'],
            ['employee_id' => 2, 'leave_type_id' => 2, 'start_date' => '2026-05-02', 'end_date' => '2026-05-02', 'reason' => 'Feeling unwell - rest day', 'status' => 'approved', 'approved_by' => 1, 'approved_at' => '2026-05-02T08:00:00'],
            ['employee_id' => 3, 'leave_type_id' => 3, 'start_date' => '2026-06-10', 'end_date' => '2026-06-10', 'reason' => 'Personal appointment', 'status' => 'pending', 'approved_by' => null, 'approved_at' => null],
        ]);

        DB::table('leave_balances')->insert([
            ['employee_id' => 1, 'leave_type_id' => 1, 'year' => 2026, 'total_days' => 20.00, 'used_days' => 0.00, 'pending_days' => 15.00],
            ['employee_id' => 1, 'leave_type_id' => 2, 'year' => 2026, 'total_days' => 10.00, 'used_days' => 1.00, 'pending_days' => 0.00],
            ['employee_id' => 2, 'leave_type_id' => 1, 'year' => 2026, 'total_days' => 20.00, 'used_days' => 1.00, 'pending_days' => 0.00],
            ['employee_id' => 2, 'leave_type_id' => 2, 'year' => 2026, 'total_days' => 10.00, 'used_days' => 1.00, 'pending_days' => 0.00],
            ['employee_id' => 3, 'leave_type_id' => 1, 'year' => 2026, 'total_days' => 20.00, 'used_days' => 0.00, 'pending_days' => 1.00],
            ['employee_id' => 3, 'leave_type_id' => 3, 'year' => 2026, 'total_days' => 5.00, 'used_days' => 0.00, 'pending_days' => 1.00],
        ]);

        DB::table('timesheets')->insert([
            ['employee_id' => 1, 'date' => '2026-05-01', 'start_time' => '08:00:00', 'end_time' => '17:00:00', 'total_hours' => 9.00, 'break_hours' => 1.00, 'description' => 'Regular work - Platform development', 'is_approved' => true, 'approved_by' => 1],
            ['employee_id' => 1, 'date' => '2026-05-02', 'start_time' => '08:30:00', 'end_time' => '17:30:00', 'total_hours' => 9.00, 'break_hours' => 1.00, 'description' => 'Code review and deployment', 'is_approved' => true, 'approved_by' => 1],
            ['employee_id' => 2, 'date' => '2026-05-01', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'total_hours' => 9.00, 'break_hours' => 1.00, 'description' => 'Sales calls and client meetings', 'is_approved' => true, 'approved_by' => 1],
            ['employee_id' => 2, 'date' => '2026-05-03', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'total_hours' => 8.00, 'break_hours' => 1.00, 'description' => 'Reporting and pipeline review', 'is_approved' => false, 'approved_by' => null],
        ]);
    }
}
