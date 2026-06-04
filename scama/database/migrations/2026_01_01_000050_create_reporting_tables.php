<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->string('slug', 255)->notNull()->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('report_categories')->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('parent_id', 'idx_report_categories_parent');
        });

        Schema::create('report_definitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->string('slug', 255)->notNull()->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('report_categories')->nullOnDelete();
            $table->enum('report_type', ['tabular', 'chart', 'pivot', 'summary'])->default('tabular');
            $table->json('config')->notNull();
            $table->boolean('is_system')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('category_id', 'idx_report_definitions_category');
            $table->index('report_type', 'idx_report_definitions_type');
        });

        Schema::create('report_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('report_id')->constrained('report_definitions')->cascadeOnDelete();
            $table->string('name', 255)->notNull();
            $table->string('cron_expression', 100)->notNull();
            $table->json('recipients')->notNull();
            $table->enum('format', ['pdf', 'csv', 'excel', 'json'])->default('pdf');
            $table->json('config')->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->enum('status', ['active', 'paused', 'completed', 'failed'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('report_id', 'idx_report_schedules_report');
            $table->index('status', 'idx_report_schedules_status');
            $table->index('next_run_at', 'idx_report_schedules_next_run');
        });

        Schema::create('dashboard_widgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('dashboard_name', 100)->default('default');
            $table->string('widget_type', 100)->notNull();
            $table->string('title', 255)->nullable();
            $table->json('config')->nullable();
            $table->integer('position_x')->default(0);
            $table->integer('position_y')->default(0);
            $table->integer('width')->default(4);
            $table->integer('height')->default(3);
            $table->timestamps();

            $table->index('user_id', 'idx_dashboard_widgets_user');
            $table->index('dashboard_name', 'idx_dashboard_widgets_dashboard');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_widgets');
        Schema::dropIfExists('report_schedules');
        Schema::dropIfExists('report_definitions');
        Schema::dropIfExists('report_categories');
    }
};
