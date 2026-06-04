<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreignId('subscription_id')->nullable()->constrained('user_subscriptions')->nullOnDelete();
            $table->string('invoice_number', 50)->nullable();
            $table->decimal('total', 10, 2);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->string('status', 20)->default('draft');
            $table->timestamps();

            $table->unique('invoice_number', 'unique_invoice_number');
            $table->index('user_id', 'idx_invoices_user');
            $table->index('order_id', 'idx_invoices_order');
            $table->index('subscription_id', 'idx_invoices_subscription');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->string('gateway', 50);
            $table->string('transaction_id', 255)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status', 20)->default('pending');
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('invoice_id', 'idx_payments_invoice');
            $table->index('gateway', 'idx_payments_gateway');
        });

        Schema::create('tax_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->decimal('rate', 5, 2);
            $table->string('type', 20)->default('percentage');
            $table->string('country', 2)->nullable();
            $table->string('region', 100)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('country', 'idx_tax_rates_country');
            $table->index('active', 'idx_tax_rates_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
    }
};
