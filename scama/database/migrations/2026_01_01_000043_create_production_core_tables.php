<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_centers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 20)->notNull();
            $table->string('name', 255)->notNull();
            $table->enum('type', ['machine', 'workstation', 'assembly_line', 'manual'])->notNull();
            $table->text('description')->nullable();
            $table->decimal('cost_per_hour', 15, 2)->default(0.00);
            $table->decimal('efficiency_rate', 5, 2)->default(100.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code', 'unique_work_centers_code');
        });

        Schema::create('work_center_capacity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('work_center_id')->constrained('work_centers')->cascadeOnDelete();
            $table->date('capacity_date')->notNull();
            $table->decimal('available_hours', 8, 2)->notNull();
            $table->decimal('maintenance_hours', 8, 2)->default(0);
            $table->decimal('booked_hours', 8, 2)->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['work_center_id', 'capacity_date'], 'unique_wc_capacity_date');
            $table->index('work_center_id', 'idx_wc_capacity_center');
        });

        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('name', 255)->notNull();
            $table->string('version', 20)->default('1.0');
            $table->decimal('quantity', 15, 4)->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('product_id', 'idx_bom_product');
            $table->index('is_active', 'idx_bom_active');
        });

        Schema::create('bom_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('bom_id')->constrained('bill_of_materials')->cascadeOnDelete();
            $table->foreignId('component_id')->constrained('products')->restrictOnDelete();
            $table->decimal('quantity', 15, 4)->notNull();
            $table->string('unit', 20)->nullable();
            $table->decimal('scrap_rate', 5, 2)->default(0.00);
            $table->integer('line_order')->default(0);
            $table->timestamps();

            $table->index('bom_id', 'idx_bom_items_bom');
            $table->index('component_id', 'idx_bom_items_component');
        });

        Schema::create('routings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('bom_id')->constrained('bill_of_materials')->cascadeOnDelete();
            $table->string('name', 255)->notNull();
            $table->decimal('total_time', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('bom_id', 'idx_routings_bom');
        });

        Schema::create('routing_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('routing_id')->constrained('routings')->cascadeOnDelete();
            $table->foreignId('work_center_id')->constrained('work_centers')->restrictOnDelete();
            $table->string('step_name', 255)->notNull();
            $table->integer('step_order')->notNull();
            $table->decimal('setup_time', 8, 2)->default(0);
            $table->decimal('run_time', 8, 2)->default(0);
            $table->decimal('teardown_time', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('routing_id', 'idx_routing_steps_routing');
            $table->index('work_center_id', 'idx_routing_steps_center');
        });

        Schema::create('capacity_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('work_center_id')->constrained('work_centers')->cascadeOnDelete();
            $table->date('plan_date')->notNull();
            $table->decimal('planned_hours', 8, 2)->notNull();
            $table->decimal('actual_hours', 8, 2)->default(0);
            $table->decimal('available_hours', 8, 2)->notNull();
            $table->decimal('load_percentage', 5, 2)->virtualAs('(planned_hours / nullif(available_hours, 0)) * 100');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['work_center_id', 'plan_date'], 'unique_capacity_plans_center_date');
            $table->index('work_center_id', 'idx_capacity_plans_center');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capacity_plans');
        Schema::dropIfExists('routing_steps');
        Schema::dropIfExists('routings');
        Schema::dropIfExists('bom_items');
        Schema::dropIfExists('bill_of_materials');
        Schema::dropIfExists('work_center_capacity');
        Schema::dropIfExists('work_centers');
    }
};
