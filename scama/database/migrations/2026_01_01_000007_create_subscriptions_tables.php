<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('code', 50)->nullable();
            $table->integer('duration_months');
            $table->integer('max_activations')->default(1);
            $table->decimal('price_monthly', 10, 2);
            $table->decimal('price_yearly', 10, 2);
            $table->json('features')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique('code', 'unique_subscription_plans_code');
            $table->index('status', 'idx_plans_status');
        });

        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('status', 20)->default('active');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('stripe_subscription_id', 255)->nullable();
            $table->timestamps();

            $table->index('user_id', 'idx_user_subscriptions_user');
            $table->index('plan_id', 'idx_user_subscriptions_plan');
            $table->index('product_id', 'idx_user_subscriptions_product');
            $table->index('status', 'idx_user_subscriptions_status');
            $table->index(['user_id', 'status'], 'idx_user_subscriptions_user_status');
        });

        Schema::create('wishlists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'product_id'], 'unique_wishlists_user_product');
            $table->index('product_id', 'idx_wishlists_product');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('subscription_plans');
    }
};
