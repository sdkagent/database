<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->string('code', 20)->notNull();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 2)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code', 'unique_warehouses_code');
        });

        Schema::create('warehouse_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('warehouse_locations')->nullOnDelete();
            $table->string('code', 50)->notNull();
            $table->string('name', 255)->nullable();
            $table->enum('type', ['aisle', 'rack', 'shelf', 'bin', 'bulk'])->default('bin');
            $table->decimal('max_weight', 10, 2)->nullable();
            $table->decimal('max_volume', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['warehouse_id', 'code'], 'unique_warehouse_locations_wh_code');
            $table->index('parent_id', 'idx_warehouse_locations_parent');
        });

        Schema::create('stock_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_location_id')->constrained('warehouse_locations')->restrictOnDelete();
            $table->string('serial_number', 100)->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->decimal('quantity', 15, 4)->default(0);
            $table->decimal('reserved_quantity', 15, 4)->default(0);
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['available', 'reserved', 'quarantine', 'damaged', 'disposed'])->default('available');
            $table->timestamps();

            $table->index('product_id', 'idx_stock_items_product');
            $table->index('warehouse_location_id', 'idx_stock_items_location');
            $table->index('serial_number', 'idx_stock_items_serial');
            $table->index('batch_number', 'idx_stock_items_batch');
            $table->index('status', 'idx_stock_items_status');
            $table->index(['product_id', 'warehouse_location_id'], 'idx_stock_items_product_location');
        });

        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('from_location_id')->nullable()->constrained('warehouse_locations')->nullOnDelete();
            $table->foreignId('to_location_id')->nullable()->constrained('warehouse_locations')->nullOnDelete();
            $table->foreignId('stock_item_id')->nullable()->constrained('stock_items')->nullOnDelete();
            $table->enum('movement_type', ['receipt', 'issue', 'transfer', 'adjustment', 'return', 'sale'])->notNull();
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('quantity', 15, 4)->notNull();
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index('product_id', 'idx_inventory_movements_product');
            $table->index('from_location_id', 'idx_inventory_movements_from');
            $table->index('to_location_id', 'idx_inventory_movements_to');
            $table->index('stock_item_id', 'idx_inventory_movements_stock');
            $table->index(['reference_type', 'reference_id'], 'idx_inventory_movements_reference');
            $table->index('created_by', 'idx_inventory_movements_creator');
            $table->index('movement_type', 'idx_inventory_movements_type');
            $table->index('created_at', 'idx_inventory_movements_created');
        });

        Schema::create('inventory_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_location_id')->constrained('warehouse_locations')->cascadeOnDelete();
            $table->enum('adjustment_type', ['count', 'damage', 'write_off', 'return', 'reclassification'])->notNull();
            $table->decimal('expected_qty', 15, 4)->notNull();
            $table->decimal('actual_qty', 15, 4)->notNull();
            $table->decimal('difference', 15, 4)->notNull();
            $table->text('reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('product_id', 'idx_inventory_adjustments_product');
            $table->index('warehouse_location_id', 'idx_inventory_adjustments_location');
            $table->index('approved_by', 'idx_inventory_adjustments_approver');
            $table->index('adjustment_type', 'idx_inventory_adjustments_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_adjustments');
        Schema::dropIfExists('inventory_movements');
        Schema::dropIfExists('stock_items');
        Schema::dropIfExists('warehouse_locations');
        Schema::dropIfExists('warehouses');
    }
};
