<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->nullable();
            $table->string('slug', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('version', 20)->nullable();
            $table->string('author', 100)->nullable();
            $table->string('status', 20)->default('inactive');
            $table->timestamps();

            $table->unique('slug', 'unique_themes_slug');
        });

        Schema::create('theme_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('theme_id')->constrained('themes')->cascadeOnDelete();
            $table->string('key', 100)->nullable();
            $table->text('value')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('theme_id', 'idx_theme_settings_theme');
        });

        Schema::create('theme_assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('theme_id')->constrained('themes')->cascadeOnDelete();
            $table->string('type', 10);
            $table->string('path');
            $table->timestamp('created_at')->useCurrent();

            $table->index('theme_id', 'idx_theme_assets_theme');
        });

        Schema::create('theme_general_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('theme_id')->constrained('themes')->cascadeOnDelete();
            $table->string('setting_key', 100)->nullable();
            $table->text('setting_value')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('theme_id', 'idx_theme_general_settings_theme');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_general_settings');
        Schema::dropIfExists('theme_assets');
        Schema::dropIfExists('theme_settings');
        Schema::dropIfExists('themes');
    }
};
