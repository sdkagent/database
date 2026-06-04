<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->notNull();
            $table->enum('provider', ['twilio', 'aws_sns', 'vonage', 'custom'])->notNull();
            $table->string('api_key', 500)->nullable();
            $table->string('api_secret', 500)->nullable();
            $table->string('from_number', 20)->nullable();
            $table->string('api_endpoint', 500)->nullable();
            $table->json('config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->unique('name', 'unique_sms_providers_name');
            $table->index('is_active', 'idx_sms_providers_active');
        });

        Schema::create('sms_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->notNull();
            $table->string('category', 50)->nullable();
            $table->text('body')->notNull();
            $table->json('variables')->nullable();
            $table->timestamps();

            $table->unique('name', 'unique_sms_templates_name');
        });

        Schema::create('sms_campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200)->notNull();
            $table->text('message_body')->notNull();
            $table->foreignId('sms_template_id')->nullable()->constrained('sms_templates')->nullOnDelete();
            $table->foreignId('provider_id')->nullable()->constrained('sms_providers')->nullOnDelete();
            $table->enum('target_type', ['all', 'selected', 'role'])->notNull();
            $table->json('target_roles')->nullable();
            $table->json('target_user_ids')->nullable();
            $table->json('filter_criteria')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'partial', 'failed', 'cancelled'])->default('draft');
            $table->integer('total_recipients')->default(0);
            $table->integer('success_count')->default(0);
            $table->integer('fail_count')->default(0);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index('status', 'idx_sms_campaigns_status');
            $table->index('scheduled_at', 'idx_sms_campaigns_scheduled');
            $table->index('created_by', 'idx_sms_campaigns_creator');
        });

        Schema::create('sms_campaign_recipients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('campaign_id')->constrained('sms_campaigns')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('phone', 50)->nullable();
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed', 'bounced'])->default('pending');
            $table->text('error_message')->nullable();
            $table->string('provider_message_id', 255)->nullable();
            $table->foreignId('provider_id')->nullable()->constrained('sms_providers')->nullOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('campaign_id', 'idx_sms_recipients_campaign');
            $table->index('user_id', 'idx_sms_recipients_user');
            $table->index('status', 'idx_sms_recipients_status');
            $table->index(['campaign_id', 'status'], 'idx_sms_recipients_campaign_status');
        });

        Schema::create('sms_automations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200)->notNull();
            $table->enum('trigger_type', ['event', 'schedule'])->notNull();
            $table->string('event_name', 100)->nullable();
            $table->json('event_conditions')->nullable();
            $table->string('cron_expression', 100)->nullable();
            $table->string('timezone', 50)->nullable()->default('UTC');
            $table->enum('target_type', ['all', 'selected', 'role', 'event_context'])->notNull();
            $table->json('target_roles')->nullable();
            $table->json('filter_criteria')->nullable();
            $table->text('message_body')->notNull();
            $table->foreignId('sms_template_id')->nullable()->constrained('sms_templates')->nullOnDelete();
            $table->foreignId('provider_id')->nullable()->constrained('sms_providers')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_triggered_at')->nullable();
            $table->integer('total_sent')->default(0);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index('is_active', 'idx_sms_automations_active');
            $table->index('event_name', 'idx_sms_automations_event');
            $table->index('created_by', 'idx_sms_automations_creator');
        });

        Schema::create('sms_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('provider_id')->nullable()->constrained('sms_providers')->nullOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained('sms_campaigns')->nullOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('sms_campaign_recipients')->nullOnDelete();
            $table->enum('direction', ['outgoing', 'callback'])->notNull();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->integer('http_status')->nullable();
            $table->string('provider_message_id', 255)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('provider_id', 'idx_sms_logs_provider');
            $table->index('campaign_id', 'idx_sms_logs_campaign');
            $table->index('provider_message_id', 'idx_sms_logs_provider_msg');
            $table->index('created_at', 'idx_sms_logs_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
        Schema::dropIfExists('sms_automations');
        Schema::dropIfExists('sms_campaign_recipients');
        Schema::dropIfExists('sms_campaigns');
        Schema::dropIfExists('sms_templates');
        Schema::dropIfExists('sms_providers');
    }
};
