<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('entry_number', 50)->notNull();
            $table->foreignId('entry_type_id')->constrained('journal_entry_types')->restrictOnDelete();
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years')->restrictOnDelete();
            $table->foreignId('account_period_id')->nullable()->constrained('account_periods')->nullOnDelete();
            $table->date('entry_date')->notNull();
            $table->text('description')->nullable();
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_posted')->default(false);
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->unique('entry_number', 'unique_journal_entries_number');
            $table->index('entry_type_id', 'idx_journal_entries_type');
            $table->index('fiscal_year_id', 'idx_journal_entries_fiscal');
            $table->index('account_period_id', 'idx_journal_entries_period');
            $table->index('entry_date', 'idx_journal_entries_date');
            $table->index(['reference_type', 'reference_id'], 'idx_journal_entries_reference');
            $table->index('created_by', 'idx_journal_entries_creator');
            $table->index('is_posted', 'idx_journal_entries_posted');
        });

        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('journal_entry_id')->constrained('journal_entries')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('chart_of_accounts')->restrictOnDelete();
            $table->foreignId('cost_center_id')->nullable()->constrained('cost_centers')->nullOnDelete();
            $table->foreignId('profit_center_id')->nullable()->constrained('profit_centers')->nullOnDelete();
            $table->decimal('debit', 15, 2)->default(0.00);
            $table->decimal('credit', 15, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->integer('line_order')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('journal_entry_id', 'idx_journal_entry_lines_entry');
            $table->index('account_id', 'idx_journal_entry_lines_account');
            $table->index('cost_center_id', 'idx_journal_entry_lines_cost');
            $table->index('profit_center_id', 'idx_journal_entry_lines_profit');
        });

        Schema::create('account_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('account_id')->constrained('chart_of_accounts')->cascadeOnDelete();
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years')->cascadeOnDelete();
            $table->foreignId('account_period_id')->nullable()->constrained('account_periods')->nullOnDelete();
            $table->enum('period_type', ['month', 'quarter', 'year'])->notNull();
            $table->decimal('opening_balance', 15, 2)->default(0.00);
            $table->decimal('period_debit', 15, 2)->default(0.00);
            $table->decimal('period_credit', 15, 2)->default(0.00);
            $table->decimal('closing_balance', 15, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['account_id', 'fiscal_year_id', 'account_period_id', 'period_type'], 'unique_account_balances_account_period');
            $table->index('account_id', 'idx_account_balances_account');
            $table->index('fiscal_year_id', 'idx_account_balances_fiscal');
            $table->index('account_period_id', 'idx_account_balances_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_balances');
        Schema::dropIfExists('journal_entry_lines');
        Schema::dropIfExists('journal_entries');
    }
};
