<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->notNull();
            $table->string('code', 20)->notNull();
            $table->enum('type', ['earning', 'deduction', 'employer_contribution'])->notNull();
            $table->enum('calculation', ['fixed', 'percentage_of_basic', 'percentage_of_gross', 'formula'])->default('fixed');
            $table->decimal('value', 15, 2)->nullable();
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code', 'unique_payroll_components_code');
        });

        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years')->restrictOnDelete();
            $table->foreignId('account_period_id')->nullable()->constrained('account_periods')->nullOnDelete();
            $table->string('run_number', 50)->notNull();
            $table->date('period_start')->notNull();
            $table->date('period_end')->notNull();
            $table->date('payment_date')->nullable();
            $table->enum('status', ['draft', 'processing', 'completed', 'cancelled'])->default('draft');
            $table->decimal('total_gross', 15, 2)->default(0.00);
            $table->decimal('total_deductions', 15, 2)->default(0.00);
            $table->decimal('total_net', 15, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('run_number', 'unique_payroll_runs_number');
            $table->index('fiscal_year_id', 'idx_payroll_runs_fiscal');
            $table->index('account_period_id', 'idx_payroll_runs_period');
            $table->index('status', 'idx_payroll_runs_status');
        });

        Schema::create('payroll_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('payroll_run_id')->constrained('payroll_runs')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->restrictOnDelete();
            $table->decimal('gross_pay', 15, 2)->notNull();
            $table->decimal('total_deductions', 15, 2)->default(0.00);
            $table->decimal('net_pay', 15, 2)->notNull();
            $table->string('bank_account', 100)->nullable();
            $table->enum('payment_method', ['bank_transfer', 'check', 'cash'])->default('bank_transfer');
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('payroll_run_id', 'idx_payroll_items_run');
            $table->index('employee_id', 'idx_payroll_items_employee');
            $table->index('status', 'idx_payroll_items_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
        Schema::dropIfExists('payroll_runs');
        Schema::dropIfExists('payroll_components');
    }
};
