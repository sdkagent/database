<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->string('slug', 255)->notNull()->unique();
            $table->text('description')->nullable();
            $table->integer('priority')->default(0);
            $table->json('conditions')->nullable();
            $table->json('adjustments')->notNull();
            $table->enum('applies_to', ['all', 'products', 'categories', 'users', 'user_roles'])->default('all');
            $table->boolean('stackable')->default(false);
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status', 'idx_price_rules_status');
            $table->index(['starts_at', 'expires_at'], 'idx_price_rules_dates');
        });

        Schema::create('price_tiers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('min_quantity')->notNull();
            $table->unsignedInteger('max_quantity')->nullable();
            $table->decimal('unit_price', 15, 2)->notNull();
            $table->timestamps();

            $table->index('product_id', 'idx_price_tiers_product');
        });

        Schema::create('price_overrides', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('subscription_plans')->nullOnDelete();
            $table->decimal('override_price', 15, 2)->notNull();
            $table->enum('override_type', ['fixed', 'percentage'])->default('fixed');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('user_id', 'idx_price_overrides_user');
            $table->index('product_id', 'idx_price_overrides_product');
            $table->index('plan_id', 'idx_price_overrides_plan');
        });

        Schema::create('price_rule_audit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('price_rule_id')->nullable()->constrained('price_rules')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('original_price', 15, 2)->notNull();
            $table->decimal('adjusted_price', 15, 2)->notNull();
            $table->string('rule_name', 255)->nullable();
            $table->json('context')->nullable();
            $table->timestamp('applied_at')->useCurrent();

            $table->index('price_rule_id', 'idx_price_rule_audit_rule');
            $table->index('order_id', 'idx_price_rule_audit_order');
            $table->index('user_id', 'idx_price_rule_audit_user');
            $table->index('product_id', 'idx_price_rule_audit_product');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_rule_audit');
        Schema::dropIfExists('price_overrides');
        Schema::dropIfExists('price_tiers');
        Schema::dropIfExists('price_rules');
    }
};
