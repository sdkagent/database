<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('parent_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('code', 20)->notNull();
            $table->string('name', 255)->notNull();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code', 'unique_departments_code');
            $table->index('parent_id', 'idx_departments_parent');
            $table->index('manager_id', 'idx_departments_manager');
        });

        Schema::create('job_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->string('title', 255)->notNull();
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->decimal('salary_min', 15, 2)->nullable();
            $table->decimal('salary_max', 15, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('department_id', 'idx_job_positions_department');
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('employee_number', 20)->notNull();
            $table->foreignId('department_id')->constrained('departments')->restrictOnDelete();
            $table->foreignId('job_position_id')->constrained('job_positions')->restrictOnDelete();
            $table->foreignId('reports_to')->nullable()->constrained('employees')->nullOnDelete();
            $table->date('hire_date')->notNull();
            $table->date('termination_date')->nullable();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern', 'temporary'])->default('full_time');
            $table->enum('status', ['active', 'on_leave', 'terminated', 'suspended'])->default('active');
            $table->decimal('base_salary', 15, 2)->nullable();
            $table->foreignId('currency_id')->constrained('currencies')->restrictOnDelete();
            $table->json('emergency_contact')->nullable();
            $table->timestamps();

            $table->unique('employee_number', 'unique_employees_number');
            $table->unique('user_id', 'unique_employees_user');
            $table->index('department_id', 'idx_employees_department');
            $table->index('job_position_id', 'idx_employees_position');
            $table->index('reports_to', 'idx_employees_reports');
            $table->index('status', 'idx_employees_status');
            $table->index('currency_id', 'idx_employees_currency');
        });

        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('contract_type', ['permanent', 'fixed_term', 'probation', 'consulting'])->notNull();
            $table->date('start_date')->notNull();
            $table->date('end_date')->nullable();
            $table->decimal('salary', 15, 2)->notNull();
            $table->foreignId('currency_id')->constrained('currencies')->restrictOnDelete();
            $table->json('benefits')->nullable();
            $table->json('documents')->nullable();
            $table->enum('status', ['active', 'expired', 'terminated'])->default('active');
            $table->timestamps();

            $table->index('employee_id', 'idx_employee_contracts_employee');
            $table->index('currency_id', 'idx_employee_contracts_currency');
        });

        Schema::create('employee_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('document_type', ['id', 'passport', 'visa', 'certificate', 'contract', 'other'])->notNull();
            $table->string('file_name', 255)->notNull();
            $table->string('file_path', 500)->notNull();
            $table->date('expiry_date')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('employee_id', 'idx_employee_documents_employee');
        });

        Schema::create('attendance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date')->notNull();
            $table->dateTime('clock_in')->nullable();
            $table->dateTime('clock_out')->nullable();
            $table->decimal('total_hours', 5, 2)->virtualAs('timestampdiff(minute, clock_in, clock_out) / 60');
            $table->enum('status', ['present', 'absent', 'late', 'half_day', 'holiday'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date'], 'unique_attendance_employee_date');
            $table->index('employee_id', 'idx_attendance_employee');
            $table->index('date', 'idx_attendance_date');
        });

        Schema::create('leave_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->notNull();
            $table->string('code', 20)->notNull();
            $table->integer('days_allowed')->notNull();
            $table->boolean('is_paid')->default(true);
            $table->boolean('carry_forward')->default(false);
            $table->integer('max_carry_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code', 'unique_leave_types_code');
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->restrictOnDelete();
            $table->date('start_date')->notNull();
            $table->date('end_date')->notNull();
            $table->integer('total_days')->virtualAs('datediff(end_date, start_date) + 1');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index('employee_id', 'idx_leave_requests_employee');
            $table->index('leave_type_id', 'idx_leave_requests_type');
            $table->index('status', 'idx_leave_requests_status');
            $table->index('approved_by', 'idx_leave_requests_approver');
            $table->index(['start_date', 'end_date'], 'idx_leave_requests_dates');
        });

        Schema::create('leave_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->restrictOnDelete();
            $table->year('year')->notNull();
            $table->decimal('total_days', 5, 1)->notNull();
            $table->decimal('used_days', 5, 1)->default(0);
            $table->decimal('pending_days', 5, 1)->default(0);
            $table->decimal('remaining_days', 5, 1)->virtualAs('total_days - used_days - pending_days');
            $table->timestamps();

            $table->unique(['employee_id', 'leave_type_id', 'year'], 'unique_leave_balances_emp_year_type');
            $table->index('employee_id', 'idx_leave_balances_employee');
            $table->index('leave_type_id', 'idx_leave_balances_type');
        });

        Schema::create('timesheets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date')->notNull();
            $table->time('start_time')->notNull();
            $table->time('end_time')->nullable();
            $table->decimal('total_hours', 5, 2)->nullable();
            $table->decimal('break_hours', 4, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('employee_id', 'idx_timesheets_employee');
            $table->index('date', 'idx_timesheets_date');
            $table->index('approved_by', 'idx_timesheets_approver');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
        Schema::dropIfExists('leave_balances');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_types');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employee_contracts');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('job_positions');
        Schema::dropIfExists('departments');
    }
};
