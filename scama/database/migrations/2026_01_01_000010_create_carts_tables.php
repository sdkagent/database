<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('user_id', 'idx_carts_user');
            $table->index('coupon_id', 'idx_carts_coupon');
            $table->index(['user_id', 'expires_at'], 'idx_carts_user_expires');
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('subscription_plans')->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['cart_id', 'product_id', 'plan_id'], 'unique_cart_items_cart_product_plan');
            $table->index('cart_id', 'idx_cart_items_cart');
            $table->index('product_id', 'idx_cart_items_product');
            $table->index('plan_id', 'idx_cart_items_plan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
