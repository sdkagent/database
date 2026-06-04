<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theme_customizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('theme_id')->constrained('themes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('custom_css')->nullable();
            $table->text('custom_js')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('theme_id', 'idx_theme_customizations_theme');
            $table->index('user_id', 'idx_theme_customizations_user');
        });

        Schema::create('theme_usage_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('theme_id')->constrained('themes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('action', 20);
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('theme_id', 'idx_theme_usage_logs_theme');
            $table->index('user_id', 'idx_theme_usage_logs_user');
        });

        Schema::create('theme_update_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('theme_id')->constrained('themes')->cascadeOnDelete();
            $table->string('version_from', 20)->nullable();
            $table->string('version_to', 20)->nullable();
            $table->text('changelog')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('theme_id', 'idx_theme_update_logs_theme');
        });

        Schema::create('theme_conflicts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('theme_id')->constrained('themes')->cascadeOnDelete();
            $table->string('conflicting_plugin', 255)->nullable();
            $table->text('description')->nullable();
            $table->timestamp('reported_at')->useCurrent();

            $table->index('theme_id', 'idx_theme_conflicts_theme');
        });

        Schema::create('theme_generations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('theme_id')->constrained('themes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('theme_name');
            $table->text('theme_description')->nullable();
            $table->string('theme_version', 20)->nullable();
            $table->string('theme_variant', 50)->nullable();
            $table->string('theme_color_scheme', 50)->nullable();
            $table->string('theme_layout', 50)->nullable();
            $table->string('theme_font', 100)->nullable();
            $table->json('theme_customization')->nullable();
            $table->string('theme_preview_url', 255)->nullable();
            $table->string('theme_download_url', 255)->nullable();
            $table->string('theme_screenshot_url', 255)->nullable();
            $table->text('theme_markdown_description')->nullable();
            $table->json('theme_template_variables')->nullable();
            $table->string('style', 10)->default('light');
            $table->string('complexity', 10)->default('moderate');
            $table->string('source', 20)->default('user_input');
            $table->string('generation_method', 10)->default('manual');
            $table->string('priority', 10)->default('medium');
            $table->integer('estimated_completion_time')->nullable();
            $table->integer('actual_completion_time')->nullable();
            $table->integer('progress')->default(0);
            $table->decimal('quality_score', 3, 2)->nullable();
            $table->string('status', 20)->default('pending');
            $table->json('generated_files')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('theme_id', 'idx_theme_generations_theme');
            $table->index('user_id', 'idx_theme_generations_user');
            $table->index('status', 'idx_theme_generations_status');
        });

        Schema::create('theme_generation_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('generation_id')->constrained('theme_generations')->cascadeOnDelete();
            $table->text('message')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('generation_id', 'idx_theme_generation_logs_generation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_generation_logs');
        Schema::dropIfExists('theme_generations');
        Schema::dropIfExists('theme_conflicts');
        Schema::dropIfExists('theme_update_logs');
        Schema::dropIfExists('theme_usage_logs');
        Schema::dropIfExists('theme_customizations');
    }
};
