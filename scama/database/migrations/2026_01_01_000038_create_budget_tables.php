<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years')->cascadeOnDelete();
            $table->foreignId('profit_center_id')->nullable()->constrained('profit_centers')->nullOnDelete();
            $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers')->nullOnDelete();
            $table->string('name', 255)->notNull();
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'active', 'locked', 'closed'])->default('draft');
            $table->timestamps();

            $table->index('fiscal_year_id', 'idx_budgets_fiscal');
            $table->index('profit_center_id', 'idx_budgets_profit');
            $table->index('cost_center_id', 'idx_budgets_cost');
            $table->index('status', 'idx_budgets_status');
        });

        Schema::create('budget_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('budget_id')->constrained('budgets')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('chart_of_accounts')->restrictOnDelete();
            $table->foreignId('period_id')->nullable()->constrained('account_periods')->nullOnDelete();
            $table->decimal('amount', 15, 2)->notNull();
            $table->timestamps();

            $table->unique(['budget_id', 'account_id', 'period_id'], 'unique_budget_lines_budget_account_period');
            $table->index('budget_id', 'idx_budget_lines_budget');
            $table->index('account_id', 'idx_budget_lines_account');
            $table->index('period_id', 'idx_budget_lines_period');
        });

        Schema::create('budget_versions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('budget_id')->constrained('budgets')->cascadeOnDelete();
            $table->integer('version')->notNull();
            $table->text('notes')->nullable();
            $table->json('snapshot')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index('budget_id', 'idx_budget_versions_budget');
            $table->index('created_by', 'idx_budget_versions_creator');
        });

        Schema::create('cost_allocations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('source_cost_center_id')->constrained('cost_centers')->cascadeOnDelete();
            $table->foreignId('target_cost_center_id')->constrained('cost_centers')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('chart_of_accounts')->restrictOnDelete();
            $table->enum('allocation_method', ['percentage', 'fixed', 'activity_based'])->default('percentage');
            $table->decimal('allocation_value', 15, 4)->notNull();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('source_cost_center_id', 'idx_cost_allocations_source');
            $table->index('target_cost_center_id', 'idx_cost_allocations_target');
            $table->index('account_id', 'idx_cost_allocations_account');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cost_allocations');
        Schema::dropIfExists('budget_versions');
        Schema::dropIfExists('budget_lines');
        Schema::dropIfExists('budgets');
    }
};
