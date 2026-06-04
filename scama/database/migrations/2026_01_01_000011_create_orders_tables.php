<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_number', 50);
            $table->string('status', 20)->default('pending');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('discount_total', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('billing_address_id')->nullable();
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->unsignedBigInteger('api_client_id')->nullable();
            $table->text('customer_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->unique('order_number', 'unique_orders_number');
            $table->index('user_id', 'idx_orders_user');
            $table->index('status', 'idx_orders_status');
            $table->index('billing_address_id', 'idx_orders_billing');
            $table->index('shipping_address_id', 'idx_orders_shipping');
            $table->index('coupon_id', 'idx_orders_coupon');
            $table->index('api_client_id', 'idx_orders_api_client');
            $table->index('paid_at', 'idx_orders_paid');
            $table->index(['user_id', 'status'], 'idx_orders_user_status');
            $table->index(['status', 'paid_at'], 'idx_orders_status_paid');
            $table->index(['user_id', 'created_at'], 'idx_orders_user_created');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('subscription_plans')->nullOnDelete();
            $table->string('item_type', 20);
            $table->string('name');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->index('order_id', 'idx_order_items_order');
            $table->index('product_id', 'idx_order_items_product');
            $table->index('plan_id', 'idx_order_items_plan');
            $table->unique(['order_id', 'product_id'], 'idx_order_items_order_product');
        });

        Schema::create('order_item_metadata', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->unsignedBigInteger('license_id')->nullable();
            $table->string('meta_key', 100);
            $table->text('meta_value')->nullable();
            $table->timestamps();

            $table->unique(['order_item_id', 'meta_key'], 'unique_order_item_metadata_item_key');
            $table->index('license_id', 'idx_order_item_metadata_license');
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('from_status', 20)->nullable();
            $table->string('to_status', 20);
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('api_client_id')->nullable();
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('order_id', 'idx_order_status_history_order');
            $table->index('changed_by', 'idx_order_status_history_changed');
            $table->index('api_client_id', 'idx_order_status_history_api_client');
            $table->index(['order_id', 'created_at'], 'idx_order_status_history_order_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('order_item_metadata');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
