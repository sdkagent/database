<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('activation_id')->nullable()->constrained('license_activations')->nullOnDelete();
            $table->string('license_key', 64)->nullable();
            $table->string('api_key', 64)->nullable();
            $table->string('ip_address', 45);
            $table->string('user_agent', 500)->nullable();
            $table->string('request_domain', 255)->nullable();
            $table->string('request_ip', 45)->nullable();
            $table->string('tier1_api', 10)->default('pass');
            $table->string('tier2_license', 10)->default('pass');
            $table->string('tier3_domain', 10)->default('pass');
            $table->string('tier4_ip', 10)->default('pass');
            $table->string('tier5_subscription', 10)->default('pass');
            $table->string('overall_result', 20)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('api_key', 'idx_verification_logs_api_key');
            $table->index('license_key', 'idx_verification_logs_license_key');
            $table->index('activation_id', 'idx_verification_logs_activation');
            $table->index('created_at', 'idx_verification_logs_created');
            $table->index(['api_key', 'license_key'], 'idx_verification_logs_key_lookup');
        });

        Schema::create('fraud_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('license_id')->nullable()->constrained('licenses')->nullOnDelete();
            $table->foreignId('activation_id')->nullable()->constrained('license_activations')->nullOnDelete();
            $table->string('ip', 45)->nullable();
            $table->string('domain', 255)->nullable();
            $table->string('reason');
            $table->string('severity', 20)->default('low');
            $table->string('action_taken', 100)->default('logged');
            $table->timestamp('created_at')->useCurrent();

            $table->index('license_id', 'idx_fraud_logs_license');
            $table->index('activation_id', 'idx_fraud_logs_activation');
            $table->index('severity', 'idx_fraud_logs_severity');
        });

        Schema::create('user_2fa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('secret', 255)->nullable();
            $table->string('method', 20)->default('totp');
            $table->json('backup_codes')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index('user_id', 'idx_user_2fa_user');
            $table->index('is_enabled', 'idx_user_2fa_enabled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_2fa');
        Schema::dropIfExists('fraud_logs');
        Schema::dropIfExists('verification_logs');
    }
};
