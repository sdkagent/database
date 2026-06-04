<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 100);
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general');
            $table->timestamps();

            $table->unique('key', 'unique_system_settings_key');
        });

        Schema::create('system_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('level', 10)->default('info');
            $table->text('message')->nullable();
            $table->json('context')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('level', 'idx_system_logs_level');
            $table->index('created_at', 'idx_system_logs_created');
        });

        Schema::create('email_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('smtp_host', 255)->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_username', 255)->nullable();
            $table->string('smtp_password', 255)->nullable();
            $table->string('from_email', 255)->nullable();
            $table->string('from_name', 255)->nullable();
            $table->string('encryption', 10)->default('none');
            $table->boolean('is_default')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index('is_default', 'idx_email_default');
        });

        Schema::create('email_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('subject', 255)->nullable();
            $table->text('body')->nullable();
            $table->timestamps();

            $table->unique('name', 'unique_email_templates_name');
        });

        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('name', 'unique_payment_gateways_name');
        });

        Schema::create('payment_gateway_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('gateway_id')->constrained('payment_gateways')->cascadeOnDelete();
            $table->string('key', 100)->nullable();
            $table->text('value')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('gateway_id', 'idx_payment_gateway_settings_gateway');
        });

        Schema::create('webhooks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('url', 500);
            $table->json('events');
            $table->string('secret', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_triggered_at')->nullable();
            $table->timestamps();

            $table->index('is_active', 'idx_webhooks_active');
        });

        Schema::create('feature_flags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('key', 100);
            $table->text('description')->nullable();
            $table->boolean('enabled')->default(false);
            $table->json('conditions')->nullable();
            $table->timestamps();

            $table->unique('name', 'unique_feature_flags_name');
            $table->unique('key', 'unique_feature_flags_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_flags');
        Schema::dropIfExists('webhooks');
        Schema::dropIfExists('payment_gateway_settings');
        Schema::dropIfExists('payment_gateways');
        Schema::dropIfExists('email_templates');
        Schema::dropIfExists('email_settings');
        Schema::dropIfExists('system_logs');
        Schema::dropIfExists('system_settings');
    }
};
