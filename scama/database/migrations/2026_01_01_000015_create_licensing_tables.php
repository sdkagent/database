<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 100)->nullable();
            $table->string('api_key', 64);
            $table->string('api_secret', 255)->nullable();
            $table->string('status', 20)->default('active');
            $table->integer('rate_limit')->default(60);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique('api_key', 'unique_api_clients_key');
            $table->index('user_id', 'idx_api_clients_user');
        });

        Schema::create('licenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('api_client_id')->nullable()->constrained('api_clients')->nullOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('user_subscriptions')->nullOnDelete();
            $table->string('license_key', 64);
            $table->string('api_key', 64);
            $table->string('status', 20)->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->integer('max_activations')->default(1);
            $table->integer('current_activations')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();

            $table->unique('license_key', 'unique_licenses_key');
            $table->index('user_id', 'idx_licenses_user');
            $table->index('product_id', 'idx_licenses_product');
            $table->index('api_client_id', 'idx_licenses_api_client');
            $table->index('subscription_id', 'idx_licenses_subscription');
            $table->index('status', 'idx_licenses_status');
            $table->index(['user_id', 'status'], 'idx_licenses_user_status');
            $table->index(['api_key', 'license_key'], 'idx_licenses_api_key');
        });

        Schema::create('license_activations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('license_id')->constrained('licenses')->cascadeOnDelete();
            $table->string('domain', 255)->nullable();
            $table->string('hosting_ip', 45)->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamp('last_verified_at')->useCurrent();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index('license_id', 'idx_license_activations_license');
            $table->index('domain', 'idx_license_activations_domain');
            $table->index('status', 'idx_license_activations_status');
            $table->index(['license_id', 'status'], 'idx_license_activations_license_status');
        });

        Schema::create('hardware_activations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('license_id')->constrained('licenses')->cascadeOnDelete();
            $table->string('machine_id', 100);
            $table->string('cpu_id', 100)->nullable();
            $table->string('motherboard_serial', 100)->nullable();
            $table->string('bios_serial', 100)->nullable();
            $table->string('disk_serial', 100)->nullable();
            $table->string('mac_address', 17)->nullable();
            $table->string('os_name', 50)->nullable();
            $table->string('os_version', 20)->nullable();
            $table->string('os_architecture', 10)->nullable();
            $table->string('cpu_name', 100)->nullable();
            $table->integer('cpu_cores')->nullable();
            $table->integer('total_memory')->nullable();
            $table->string('system_manufacturer', 100)->nullable();
            $table->string('system_model', 100)->nullable();
            $table->string('local_ip', 45)->nullable();
            $table->string('public_ip', 45)->nullable();
            $table->string('status', 20)->default('active');
            $table->integer('activation_limit')->default(1);
            $table->timestamp('activated_at')->useCurrent();
            $table->timestamp('last_ping_at')->useCurrent();
            $table->string('required_os_min', 50)->nullable();
            $table->integer('required_memory_mb')->nullable();
            $table->integer('required_disk_mb')->nullable();
            $table->string('compatibility_status', 20)->default('unknown');
            $table->timestamps();

            $table->unique(['license_id', 'machine_id'], 'unique_hardware_activations_license_machine');
            $table->index('license_id', 'idx_hardware_activations_license');
            $table->index('status', 'idx_hardware_activations_status');
            $table->index(['license_id', 'status'], 'idx_hardware_activations_license_status');
        });

        Schema::create('hardware_activation_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('hardware_activation_id')->nullable()->constrained('hardware_activations')->nullOnDelete();
            $table->foreignId('license_id')->constrained('licenses')->cascadeOnDelete();
            $table->string('machine_id', 100);
            $table->json('hardware_snapshot')->nullable();
            $table->json('system_specs')->nullable();
            $table->string('activation_status', 30);
            $table->string('failure_reason', 255)->nullable();
            $table->boolean('vm_detected')->default(false);
            $table->boolean('tamper_detected')->default(false);
            $table->string('compatibility_result', 20)->default('unknown');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['license_id', 'machine_id'], 'idx_hardware_activation_logs_license_machine');
            $table->index('hardware_activation_id', 'idx_hardware_activation_logs_activation');
            $table->index('created_at', 'idx_hardware_activation_logs_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hardware_activation_logs');
        Schema::dropIfExists('hardware_activations');
        Schema::dropIfExists('license_activations');
        Schema::dropIfExists('licenses');
        Schema::dropIfExists('api_clients');
    }
};
