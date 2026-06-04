<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('bom_id')->constrained('bill_of_materials')->restrictOnDelete();
            $table->foreignId('routing_id')->nullable()->constrained('routings')->nullOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->string('order_number', 50)->notNull();
            $table->decimal('quantity', 15, 4)->notNull();
            $table->decimal('produced_qty', 15, 4)->default(0);
            $table->decimal('scrap_qty', 15, 4)->default(0);
            $table->enum('status', ['planned', 'released', 'in_progress', 'completed', 'cancelled', 'on_hold'])->default('planned');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->dateTime('scheduled_start')->nullable();
            $table->dateTime('scheduled_end')->nullable();
            $table->dateTime('actual_start')->nullable();
            $table->dateTime('actual_end')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('order_number', 'unique_production_orders_number');
            $table->index('product_id', 'idx_production_orders_product');
            $table->index('bom_id', 'idx_production_orders_bom');
            $table->index('routing_id', 'idx_production_orders_routing');
            $table->index('warehouse_id', 'idx_production_orders_warehouse');
            $table->index('status', 'idx_production_orders_status');
            $table->index(['scheduled_start', 'scheduled_end'], 'idx_production_orders_schedule');
        });

        Schema::create('production_order_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('production_order_id')->constrained('production_orders')->cascadeOnDelete();
            $table->foreignId('routing_step_id')->constrained('routing_steps')->restrictOnDelete();
            $table->foreignId('work_center_id')->constrained('work_centers')->restrictOnDelete();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'skipped'])->default('pending');
            $table->decimal('actual_setup_time', 8, 2)->nullable();
            $table->decimal('actual_run_time', 8, 2)->nullable();
            $table->decimal('completed_qty', 15, 4)->default(0);
            $table->decimal('scrap_qty', 15, 4)->default(0);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('production_order_id', 'idx_prod_order_steps_order');
            $table->index('routing_step_id', 'idx_prod_order_steps_step');
            $table->index('work_center_id', 'idx_prod_order_steps_center');
            $table->index('status', 'idx_prod_order_steps_status');
        });

        Schema::create('production_outputs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('production_order_id')->constrained('production_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('warehouse_location_id')->constrained('warehouse_locations')->restrictOnDelete();
            $table->decimal('quantity', 15, 4)->notNull();
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('production_order_id', 'idx_production_outputs_order');
            $table->index('product_id', 'idx_production_outputs_product');
            $table->index('warehouse_location_id', 'idx_production_outputs_location');
        });

        Schema::create('production_material_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('production_order_id')->constrained('production_orders')->cascadeOnDelete();
            $table->foreignId('stock_item_id')->nullable()->constrained('stock_items')->nullOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('warehouse_location_id')->constrained('warehouse_locations')->restrictOnDelete();
            $table->decimal('quantity', 15, 4)->notNull();
            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->timestamp('issued_at')->useCurrent();

            $table->index('production_order_id', 'idx_prod_mat_issues_order');
            $table->index('stock_item_id', 'idx_prod_mat_issues_stock');
            $table->index('product_id', 'idx_prod_mat_issues_product');
            $table->index('warehouse_location_id', 'idx_prod_mat_issues_location');
        });

        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('work_center_id')->constrained('work_centers')->cascadeOnDelete();
            $table->string('title', 255)->notNull();
            $table->enum('type', ['preventive', 'predictive', 'corrective', 'emergency'])->notNull();
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly', 'hours'])->notNull();
            $table->integer('frequency_value')->nullable();
            $table->dateTime('last_done_at')->nullable();
            $table->dateTime('next_due_at')->nullable();
            $table->decimal('estimated_hours', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('work_center_id', 'idx_maintenance_schedules_center');
            $table->index('next_due_at', 'idx_maintenance_schedules_due');
        });

        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('maintenance_schedule_id')->nullable()->constrained('maintenance_schedules')->nullOnDelete();
            $table->foreignId('work_center_id')->constrained('work_centers')->cascadeOnDelete();
            $table->string('title', 255)->notNull();
            $table->text('description')->nullable();
            $table->enum('type', ['preventive', 'predictive', 'corrective', 'emergency'])->notNull();
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->decimal('duration_hours', 8, 2)->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('maintenance_schedule_id', 'idx_maintenance_logs_schedule');
            $table->index('work_center_id', 'idx_maintenance_logs_center');
            $table->index('status', 'idx_maintenance_logs_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
        Schema::dropIfExists('maintenance_schedules');
        Schema::dropIfExists('production_material_issues');
        Schema::dropIfExists('production_outputs');
        Schema::dropIfExists('production_order_steps');
        Schema::dropIfExists('production_orders');
    }
};
