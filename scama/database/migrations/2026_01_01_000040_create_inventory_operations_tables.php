<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_counts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->date('count_date')->notNull();
            $table->enum('status', ['planned', 'in_progress', 'completed', 'verified'])->default('planned');
            $table->foreignId('counted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('warehouse_id', 'idx_stock_counts_warehouse');
            $table->index('status', 'idx_stock_counts_status');
        });

        Schema::create('stock_count_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('stock_count_id')->constrained('stock_counts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('warehouse_locations')->cascadeOnDelete();
            $table->decimal('expected_qty', 15, 4)->notNull();
            $table->decimal('counted_qty', 15, 4)->nullable();
            $table->decimal('difference', 15, 4)->virtualAs('counted_qty - expected_qty');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('stock_count_id', 'idx_stock_count_items_count');
            $table->index('product_id', 'idx_stock_count_items_product');
            $table->index('location_id', 'idx_stock_count_items_location');
        });

        Schema::create('reorder_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->decimal('min_quantity', 15, 4)->notNull();
            $table->decimal('max_quantity', 15, 4)->notNull();
            $table->decimal('reorder_point', 15, 4)->notNull();
            $table->decimal('reorder_qty', 15, 4)->notNull();
            $table->integer('lead_time_days')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'warehouse_id'], 'unique_reorder_rules_product_warehouse');
            $table->index('product_id', 'idx_reorder_rules_product');
            $table->index('warehouse_id', 'idx_reorder_rules_warehouse');
        });

        Schema::create('transfer_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('from_warehouse_id')->constrained('warehouses')->restrictOnDelete();
            $table->foreignId('to_warehouse_id')->constrained('warehouses')->restrictOnDelete();
            $table->string('transfer_number', 50)->notNull();
            $table->enum('status', ['draft', 'pending', 'approved', 'in_transit', 'completed', 'cancelled'])->default('draft');
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('transfer_number', 'unique_transfer_orders_number');
            $table->index('from_warehouse_id', 'idx_transfer_orders_from');
            $table->index('to_warehouse_id', 'idx_transfer_orders_to');
            $table->index('status', 'idx_transfer_orders_status');
        });

        Schema::create('transfer_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('transfer_order_id')->constrained('transfer_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('stock_item_id')->nullable()->constrained('stock_items')->nullOnDelete();
            $table->decimal('quantity', 15, 4)->notNull();
            $table->decimal('received_qty', 15, 4)->default(0);
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('transfer_order_id', 'idx_transfer_order_items_order');
            $table->index('product_id', 'idx_transfer_order_items_product');
            $table->index('stock_item_id', 'idx_transfer_order_items_stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_order_items');
        Schema::dropIfExists('transfer_orders');
        Schema::dropIfExists('reorder_rules');
        Schema::dropIfExists('stock_count_items');
        Schema::dropIfExists('stock_counts');
    }
};
