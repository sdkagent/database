<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_request_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('api_client_id')->nullable()->constrained('api_clients')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('endpoint', 255)->nullable();
            $table->string('method', 10)->nullable();
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->integer('status_code')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('api_client_id', 'idx_api_request_logs_client');
            $table->index('order_id', 'idx_api_request_logs_order');
            $table->index('endpoint', 'idx_api_request_logs_endpoint');
            $table->index('created_at', 'idx_api_request_logs_created');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('channel', 10)->default('in_app');
            $table->string('action_url', 500)->nullable();
            $table->string('action_text', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id', 'idx_notifications_user');
            $table->index('type', 'idx_notifications_type');
            $table->index('is_read', 'idx_notifications_read');
            $table->index(['user_id', 'is_read'], 'idx_notifications_user_read');
            $table->index(['user_id', 'created_at'], 'idx_notifications_user_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('api_request_logs');
    }
};
