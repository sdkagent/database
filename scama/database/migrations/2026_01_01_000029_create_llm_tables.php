<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('llm_providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('api_key');
            $table->string('base_url');
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        Schema::create('llm_provider_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('provider_id')->constrained('llm_providers')->cascadeOnDelete();
            $table->string('setting_key', 100);
            $table->text('setting_value');
            $table->timestamp('created_at')->useCurrent();

            $table->index('provider_id', 'idx_llm_provider_settings_provider');
        });

        Schema::create('llm_provider_activity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('provider_id')->constrained('llm_providers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('activity_type', 20);
            $table->json('details')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('provider_id', 'idx_llm_provider_activity_provider');
            $table->index('user_id', 'idx_llm_provider_activity_user');
            $table->index(['provider_id', 'activity_type'], 'idx_llm_provider_activity_provider_activity');
        });

        Schema::create('llm_provider_usage', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('provider_id')->constrained('llm_providers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('tokens_used');
            $table->timestamp('created_at')->useCurrent();

            $table->index('provider_id', 'idx_llm_provider_usage_provider');
            $table->index('user_id', 'idx_llm_provider_usage_user');
            $table->index(['provider_id', 'user_id'], 'idx_llm_provider_usage_provider_user');
        });

        Schema::create('llm_provider_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('provider_id')->constrained('llm_providers')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('prompt');
            $table->text('response');
            $table->timestamp('created_at')->useCurrent();

            $table->index('provider_id', 'idx_llm_provider_logs_provider');
            $table->index('user_id', 'idx_llm_provider_logs_user');
            $table->index('created_at', 'idx_llm_provider_logs_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('llm_provider_logs');
        Schema::dropIfExists('llm_provider_usage');
        Schema::dropIfExists('llm_provider_activity');
        Schema::dropIfExists('llm_provider_settings');
        Schema::dropIfExists('llm_providers');
    }
};
