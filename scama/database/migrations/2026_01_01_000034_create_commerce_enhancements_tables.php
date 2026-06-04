<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('platform', 20)->notNull();
            $table->string('device_token', 500)->notNull();
            $table->string('device_name', 255)->nullable();
            $table->string('fingerprint', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamp('last_notified_at')->nullable();
            $table->timestamps();

            $table->unique('device_token', 'unique_user_devices_token');
            $table->unique(['user_id', 'fingerprint'], 'unique_user_devices_fingerprint');
            $table->index('user_id', 'idx_user_devices_user');
            $table->index('is_active', 'idx_user_devices_active');
        });

        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('wishlist_id')->constrained('wishlists')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['wishlist_id', 'product_id'], 'unique_wishlist_items');
            $table->index('product_id', 'idx_wishlist_items_product');
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reviewable_type', 50)->notNull();
            $table->unsignedBigInteger('reviewable_id')->notNull();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->unsignedTinyInteger('rating')->notNull();
            $table->string('title', 255)->nullable();
            $table->text('body')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_verified_purchase')->default(false);
            $table->unsignedInteger('helpful_count')->default(0);
            $table->timestamps();

            $table->index(['reviewable_type', 'reviewable_id'], 'idx_reviews_reviewable');
            $table->index('user_id', 'idx_reviews_user');
            $table->index('rating', 'idx_reviews_rating');
            $table->index('is_approved', 'idx_reviews_approved');
            $table->index(['reviewable_type', 'rating'], 'idx_reviews_type_rating');
            $table->index('order_id', 'idx_reviews_order');
        });

        Schema::create('return_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained('order_items')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('reason', ['defective', 'wrong_item', 'not_as_described', 'changed_mind', 'other'])->notNull();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'received', 'rejected', 'refunded'])->default('pending');
            $table->enum('resolution', ['refund', 'replacement', 'store_credit'])->default('refund');
            $table->text('admin_notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('order_id', 'idx_return_requests_order');
            $table->index('user_id', 'idx_return_requests_user');
            $table->index('status', 'idx_return_requests_status');
            $table->index('processed_by', 'idx_return_requests_processor');
            $table->index('order_item_id', 'idx_return_requests_order_item');
        });

        Schema::create('user_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('label', 50)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('full_name', 255)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address_line1', 255)->notNull();
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 100)->notNull();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->notNull();
            $table->boolean('is_default_billing')->default(false);
            $table->boolean('is_default_shipping')->default(false);
            $table->timestamps();

            $table->index('user_id', 'idx_user_addresses_user');
            $table->index(['user_id', 'is_default_billing'], 'idx_user_addresses_default_billing');
            $table->index(['user_id', 'is_default_shipping'], 'idx_user_addresses_default_shipping');
        });

        Schema::create('user_payment_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('gateway_id')->nullable()->constrained('payment_gateways')->nullOnDelete();
            $table->enum('method_type', ['card', 'paypal', 'bank', 'crypto'])->notNull();
            $table->string('gateway_token', 500)->nullable();
            $table->string('display_name', 100)->nullable();
            $table->string('last_four', 4)->nullable();
            $table->string('expiry_month', 2)->nullable();
            $table->string('expiry_year', 4)->nullable();
            $table->string('card_brand', 50)->nullable();
            $table->boolean('is_default')->default(false);
            $table->foreignId('billing_address_id')->nullable()->constrained('user_addresses')->nullOnDelete();
            $table->timestamps();

            $table->index('user_id', 'idx_user_payment_methods_user');
            $table->index(['user_id', 'is_default'], 'idx_user_payment_methods_default');
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 255)->notNull();
            $table->string('token', 64)->notNull();
            $table->json('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique('token', 'unique_personal_access_tokens_token');
            $table->index('user_id', 'idx_personal_access_tokens_user');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 128)->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload')->notNull();
            $table->unsignedInteger('last_activity')->notNull();

            $table->index('user_id', 'idx_sessions_user');
            $table->index('last_activity', 'idx_sessions_activity');
        });

        Schema::create('social_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('provider', 50)->notNull();
            $table->string('provider_id', 255)->notNull();
            $table->string('provider_email', 255)->nullable();
            $table->string('avatar_url', 500)->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'provider_id'], 'unique_social_accounts_provider');
            $table->index('user_id', 'idx_social_accounts_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('user_payment_methods');
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('return_requests');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('user_devices');
    }
};
