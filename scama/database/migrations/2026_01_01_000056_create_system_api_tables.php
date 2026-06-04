<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_limit_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->string('route_pattern', 255)->notNull();
            $table->string('http_method', 10)->nullable();
            $table->unsignedInteger('max_requests')->notNull();
            $table->unsignedInteger('window_seconds')->notNull();
            $table->unsignedInteger('response_code')->default(429);
            $table->string('response_message', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active', 'idx_rate_limit_rules_active');
            $table->index('route_pattern', 'idx_rate_limit_rules_route');
        });

        Schema::create('rate_limit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('rule_id')->nullable()->constrained('rate_limit_rules')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->notNull();
            $table->string('route', 255)->notNull();
            $table->string('http_method', 10)->nullable();
            $table->string('identifier', 255)->nullable();
            $table->timestamp('hit_at')->useCurrent();

            $table->index('rule_id', 'idx_rate_limit_logs_rule');
            $table->index('user_id', 'idx_rate_limit_logs_user');
            $table->index('ip_address', 'idx_rate_limit_logs_ip');
            $table->index('hit_at', 'idx_rate_limit_logs_time');
        });

        Schema::create('health_checks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('check_type', ['database', 'cache', 'queue', 'storage', 'api', 'mail', 'search'])->notNull();
            $table->enum('status', ['pass', 'warn', 'fail'])->notNull();
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('checked_at')->useCurrent();

            $table->index('check_type', 'idx_health_checks_type');
            $table->index('status', 'idx_health_checks_status');
            $table->index('checked_at', 'idx_health_checks_time');
        });

        Schema::create('maintenance_windows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255)->notNull();
            $table->text('description')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamp('starts_at')->notNull();
            $table->timestamp('ends_at')->notNull();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status', 'idx_maintenance_windows_status');
            $table->index(['starts_at', 'ends_at'], 'idx_maintenance_windows_dates');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_windows');
        Schema::dropIfExists('health_checks');
        Schema::dropIfExists('rate_limit_logs');
        Schema::dropIfExists('rate_limit_rules');
    }
};
