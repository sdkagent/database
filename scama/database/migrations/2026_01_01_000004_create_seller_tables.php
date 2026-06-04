<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_profiles', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('store_name', 100)->nullable();
            $table->text('store_description')->nullable();
            $table->string('store_logo_url', 500)->nullable();
            $table->string('store_cover_url', 500)->nullable();
            $table->string('status', 20)->default('pending');
            $table->decimal('current_balance', 15, 4)->default(0.0000);
            $table->decimal('default_commission', 5, 2)->default(80.00);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->primary('user_id');
            $table->index('status', 'idx_seller_profiles_status');
        });

        Schema::create('payout_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('seller_id')->constrained('seller_profiles', 'user_id')->cascadeOnDelete();
            $table->string('method', 20);
            $table->string('account_label', 100)->nullable();
            $table->json('account_details')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->index('seller_id', 'idx_payout_accounts_seller');
            $table->index(['seller_id', 'is_default'], 'idx_payout_accounts_seller_default');
        });

        Schema::create('payout_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('seller_id')->constrained('seller_profiles', 'user_id')->cascadeOnDelete();
            $table->foreignId('payout_account_id')->nullable()->constrained('payout_accounts')->nullOnDelete();
            $table->decimal('amount', 15, 2);
            $table->decimal('fee', 15, 2)->default(0.00);
            $table->decimal('net_amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('reference', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('seller_id', 'idx_payout_transactions_seller');
            $table->index('payout_account_id', 'idx_payout_transactions_account');
            $table->index('status', 'idx_payout_transactions_status');
            $table->index(['seller_id', 'status'], 'idx_payout_transactions_seller_status');
        });

        Schema::create('balance_ledger', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('seller_id')->nullable()->constrained('seller_profiles', 'user_id')->nullOnDelete();
            $table->string('type', 30);
            $table->decimal('amount', 15, 4);
            $table->decimal('balance_before', 15, 4);
            $table->decimal('balance_after', 15, 4);
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('seller_id', 'idx_balance_ledger_seller');
            $table->index('type', 'idx_balance_ledger_type');
            $table->index(['reference_type', 'reference_id'], 'idx_balance_ledger_reference');
            $table->index(['seller_id', 'created_at'], 'idx_balance_ledger_seller_created');
        });

        Schema::create('seller_verification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('seller_id')->constrained('seller_profiles', 'user_id')->cascadeOnDelete();
            $table->string('document_type', 20);
            $table->string('document_url', 500);
            $table->string('status', 20)->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index('seller_id', 'idx_seller_verification_seller');
            $table->index('status', 'idx_seller_verification_status');
            $table->index(['seller_id', 'status'], 'idx_seller_verification_seller_status');
            $table->index('verified_by', 'idx_seller_verification_verified_by');
        });

        Schema::create('seller_stats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('seller_id')->constrained('seller_profiles', 'user_id')->cascadeOnDelete();
            $table->string('period_type', 10);
            $table->date('period_date');
            $table->decimal('total_sales', 15, 2)->default(0.00);
            $table->decimal('total_earnings', 15, 2)->default(0.00);
            $table->integer('total_orders')->default(0);
            $table->integer('total_products')->default(0);
            $table->decimal('avg_rating', 3, 2)->nullable();
            $table->integer('review_count')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['seller_id', 'period_type', 'period_date'], 'unique_seller_stats_seller_period');
            $table->index('seller_id', 'idx_seller_stats_seller');
            $table->index(['period_type', 'period_date'], 'idx_seller_stats_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_stats');
        Schema::dropIfExists('seller_verification');
        Schema::dropIfExists('balance_ledger');
        Schema::dropIfExists('payout_transactions');
        Schema::dropIfExists('payout_accounts');
        Schema::dropIfExists('seller_profiles');
    }
};
