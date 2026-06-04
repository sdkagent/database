<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('workflow_run_id')->constrained('workflow_runs')->cascadeOnDelete();
            $table->foreignId('node_id')->constrained('workflow_nodes')->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('responded_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('workflow_run_id', 'idx_approval_requests_run');
            $table->index('node_id', 'idx_approval_requests_node');
            $table->index('status', 'idx_approval_requests_status');
            $table->index('requested_by', 'idx_approval_requests_requested_by');
        });

        Schema::create('approval_stages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('approval_request_id')->constrained('approval_requests')->cascadeOnDelete();
            $table->unsignedInteger('stage_order')->notNull();
            $table->enum('status', ['pending', 'approved', 'rejected', 'skipped'])->default('pending');
            $table->enum('strategy', ['any', 'all'])->default('all');
            $table->unsignedInteger('min_approvers')->default(1);
            $table->timestamps();

            $table->index('approval_request_id', 'idx_approval_stages_request');
        });

        Schema::create('approval_assignees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('stage_id')->constrained('approval_stages')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('stage_id', 'idx_approval_assignees_stage');
            $table->index('user_id', 'idx_approval_assignees_user');
        });

        Schema::create('email_automations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->string('trigger_event', 100)->notNull();
            $table->foreignId('email_template_id')->nullable()->constrained('email_templates')->nullOnDelete();
            $table->json('conditions')->nullable();
            $table->json('audience_filter')->nullable();
            $table->string('sender_name', 255)->nullable();
            $table->string('sender_email', 255)->nullable();
            $table->string('reply_to', 255)->nullable();
            $table->enum('status', ['draft', 'active', 'paused', 'archived'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('trigger_event', 'idx_email_automations_event');
            $table->index('status', 'idx_email_automations_status');
            $table->index('email_template_id', 'idx_email_automations_template');
        });

        Schema::create('scheduled_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->text('description')->nullable();
            $table->string('cron_expression', 100)->notNull();
            $table->enum('task_type', ['run_workflow', 'call_webhook', 'send_report', 'run_sql', 'custom'])->notNull();
            $table->json('config')->nullable();
            $table->enum('status', ['active', 'paused', 'completed', 'failed'])->default('active');
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->boolean('is_system')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status', 'idx_scheduled_tasks_status');
            $table->index('next_run_at', 'idx_scheduled_tasks_next_run');
            $table->index('task_type', 'idx_scheduled_tasks_type');
        });

        Schema::create('webhook_delivery_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('webhook_id')->constrained('webhooks')->cascadeOnDelete();
            $table->string('event_type', 100)->notNull();
            $table->json('payload')->nullable();
            $table->json('request_headers')->nullable();
            $table->unsignedInteger('response_status')->nullable();
            $table->text('response_body')->nullable();
            $table->unsignedInteger('attempt')->default(1);
            $table->boolean('success')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('next_retry_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('webhook_id', 'idx_webhook_delivery_logs_webhook');
            $table->index('success', 'idx_webhook_delivery_logs_success');
            $table->index('event_type', 'idx_webhook_delivery_logs_event');
        });

        Schema::create('payroll_item_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('payroll_item_id')->constrained('payroll_items')->cascadeOnDelete();
            $table->foreignId('payroll_component_id')->constrained('payroll_components')->restrictOnDelete();
            $table->decimal('amount', 15, 2)->notNull();
            $table->timestamp('created_at')->useCurrent();

            $table->index('payroll_item_id', 'idx_payroll_item_details_item');
            $table->index('payroll_component_id', 'idx_payroll_item_details_component');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_item_details');
        Schema::dropIfExists('webhook_delivery_logs');
        Schema::dropIfExists('scheduled_tasks');
        Schema::dropIfExists('email_automations');
        Schema::dropIfExists('approval_assignees');
        Schema::dropIfExists('approval_stages');
        Schema::dropIfExists('approval_requests');
    }
};
