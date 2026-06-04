<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 3)->notNull();
            $table->string('name', 100)->notNull();
            $table->string('symbol', 10)->nullable();
            $table->tinyInteger('decimal_places')->default(2);
            $table->boolean('is_base')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code', 'unique_currencies_code');
        });

        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('from_currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->foreignId('to_currency_id')->constrained('currencies')->cascadeOnDelete();
            $table->decimal('rate', 15, 6)->notNull();
            $table->date('date')->notNull();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['from_currency_id', 'to_currency_id', 'date'], 'unique_exchange_rates_pair_date');
            $table->index('from_currency_id', 'idx_exchange_rates_from');
            $table->index('to_currency_id', 'idx_exchange_rates_to');
            $table->index('date', 'idx_exchange_rates_date');
        });

        Schema::create('fiscal_years', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->notNull();
            $table->date('start_date')->notNull();
            $table->date('end_date')->notNull();
            $table->boolean('is_closed')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->unique('name', 'unique_fiscal_years_name');
            $table->index(['start_date', 'end_date'], 'idx_fiscal_years_dates');
        });

        Schema::create('account_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years')->cascadeOnDelete();
            $table->enum('type', ['month', 'quarter', 'year'])->notNull();
            $table->date('start_date')->notNull();
            $table->date('end_date')->notNull();
            $table->boolean('is_closed')->default(false);
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['fiscal_year_id', 'type', 'start_date'], 'unique_account_periods_fiscal_type_dates');
            $table->index('fiscal_year_id', 'idx_account_periods_fiscal');
            $table->index(['start_date', 'end_date'], 'idx_account_periods_dates');
            $table->index('is_closed', 'idx_account_periods_closed');
        });

        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('parent_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->string('account_code', 20)->notNull();
            $table->string('account_name', 255)->notNull();
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense'])->notNull();
            $table->string('subtype', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_control')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique('account_code', 'unique_chart_of_accounts_code');
            $table->index('parent_id', 'idx_chart_of_accounts_parent');
            $table->index('type', 'idx_chart_of_accounts_type');
            $table->index('is_active', 'idx_chart_of_accounts_active');
        });

        Schema::create('cost_centers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 20)->notNull();
            $table->string('name', 255)->notNull();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code', 'unique_cost_centers_code');
        });

        Schema::create('profit_centers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 20)->notNull();
            $table->string('name', 255)->notNull();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code', 'unique_profit_centers_code');
        });

        Schema::create('journal_entry_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->notNull();
            $table->string('code', 20)->notNull();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique('code', 'unique_journal_entry_types_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entry_types');
        Schema::dropIfExists('profit_centers');
        Schema::dropIfExists('cost_centers');
        Schema::dropIfExists('chart_of_accounts');
        Schema::dropIfExists('account_periods');
        Schema::dropIfExists('fiscal_years');
        Schema::dropIfExists('exchange_rates');
        Schema::dropIfExists('currencies');
    }
};
