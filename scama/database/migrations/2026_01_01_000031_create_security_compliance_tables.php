<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event_type', 100);
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id', 'idx_security_events_user');
            $table->index('event_type', 'idx_security_events_type');
            $table->index('created_at', 'idx_security_events_created');
            $table->index(['user_id', 'event_type'], 'idx_security_events_user_type');
        });

        Schema::create('vulnerability_scans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('scan_type', 10);
            $table->string('target');
            $table->string('status', 20)->default('pending');
            $table->json('results')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('penetration_tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('test_type', 20);
            $table->string('target');
            $table->string('status', 20)->default('pending');
            $table->json('findings')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('compliance_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('report_type', 10);
            $table->string('status', 20)->default('pending');
            $table->json('findings')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('security_incidents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('incident_type', 30);
            $table->text('description');
            $table->string('status', 20)->default('open');
            $table->timestamp('reported_at')->useCurrent();
        });

        Schema::create('audit_trails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 255)->nullable();
            $table->string('entity', 255)->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('activity_type', 100)->nullable();
            $table->text('description')->nullable();
            $table->json('details')->nullable();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id', 'idx_audit_trails_user');
            $table->index(['entity', 'entity_id'], 'idx_audit_trails_entity');
            $table->index('activity_type', 'idx_audit_trails_type');
            $table->index('created_at', 'idx_audit_trails_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
        Schema::dropIfExists('security_incidents');
        Schema::dropIfExists('compliance_reports');
        Schema::dropIfExists('penetration_tests');
        Schema::dropIfExists('vulnerability_scans');
        Schema::dropIfExists('security_events');
    }
};
