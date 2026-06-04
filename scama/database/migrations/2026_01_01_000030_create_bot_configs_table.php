<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bot_configs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('platform', 20);
            $table->string('platform_token', 512);
            $table->string('platform_username', 100)->nullable();
            $table->string('webhook_url', 500)->nullable();
            $table->foreignId('llm_provider_id')->nullable()->constrained('llm_providers')->nullOnDelete();
            $table->text('llm_system_prompt')->nullable();
            $table->text('welcome_message')->nullable();
            $table->string('status', 20)->default('inactive');
            $table->json('allowed_user_ids')->nullable();
            $table->integer('rate_limit_per_minute')->default(30);
            $table->integer('max_conversation_length')->default(50);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('llm_provider_id', 'idx_bot_configs_llm_provider');
            $table->index('platform', 'idx_bot_configs_platform');
            $table->index('status', 'idx_bot_configs_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_configs');
    }
};
