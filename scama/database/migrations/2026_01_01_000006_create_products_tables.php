<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('seller_id')->nullable()->constrained('seller_profiles', 'user_id')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('type', 20);
            $table->decimal('base_price', 10, 2)->default(0.00);
            $table->string('sku', 100)->nullable();
            $table->unsignedInteger('stock')->nullable();
            $table->unsignedInteger('download_limit')->nullable();
            $table->unsignedInteger('total_sales')->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0.00);
            $table->unsignedInteger('review_count')->default(0);
            $table->string('version', 20)->nullable();
            $table->string('download_url', 500)->nullable();
            $table->string('status', 20)->default('draft');
            $table->string('demo_url', 500)->nullable();
            $table->string('docs_url', 500)->nullable();
            $table->timestamps();

            $table->unique('slug', 'unique_products_slug');
            $table->unique('sku', 'unique_products_sku');
            $table->index('seller_id', 'idx_products_seller');
            $table->index('status', 'idx_products_status');
            $table->index(['seller_id', 'status'], 'idx_products_seller_status');
        });

        Schema::create('product_hardware_requirements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('os_name', 50)->nullable();
            $table->string('os_version_min', 20)->nullable();
            $table->integer('cpu_cores_min')->nullable();
            $table->integer('memory_mb_min')->nullable();
            $table->integer('disk_mb_min')->nullable();
            $table->text('additional_notes')->nullable();

            $table->index('product_id', 'idx_product_hardware_requirements_product');
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique('slug', 'unique_product_categories_slug');
            $table->index('parent_id', 'idx_product_categories_parent');
        });

        Schema::create('product_category_items', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('product_categories')->cascadeOnDelete();

            $table->primary(['product_id', 'category_id']);
            $table->index('category_id', 'idx_prod_cat_items_category');
        });

        Schema::create('product_discounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('name');
            $table->string('type', 20);
            $table->decimal('value', 10, 2);
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index('product_id', 'idx_product_discounts_product');
            $table->index(['starts_at', 'ends_at'], 'idx_product_discounts_dates');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_discounts');
        Schema::dropIfExists('product_category_items');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('product_hardware_requirements');
        Schema::dropIfExists('products');
    }
};
