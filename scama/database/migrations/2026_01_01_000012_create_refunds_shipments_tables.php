<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->text('reason')->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('order_id', 'idx_refunds_order');
            $table->index('payment_id', 'idx_refunds_payment');
            $table->index('processed_by', 'idx_refunds_processed');
            $table->index(['order_id', 'status'], 'idx_refunds_order_status');
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('tracking_number', 255)->nullable();
            $table->string('carrier', 100)->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->json('shipping_data')->nullable();
            $table->timestamps();

            $table->index('order_id', 'idx_shipments_order');
            $table->index('status', 'idx_shipments_status');
            $table->index('tracking_number', 'idx_shipments_tracking');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('refunds');
    }
};
