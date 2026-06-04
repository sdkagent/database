<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moderation_queue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('content_type', 50)->notNull();
            $table->unsignedBigInteger('content_id')->notNull();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'reviewed', 'approved', 'rejected', 'escalated'])->default('pending');
            $table->enum('priority', ['low', 'normal', 'high', 'critical'])->default('normal');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status', 'idx_moderation_queue_status');
            $table->index('priority', 'idx_moderation_queue_priority');
            $table->index(['content_type', 'content_id'], 'idx_moderation_queue_content');
            $table->index('assigned_to', 'idx_moderation_queue_assignee');
        });

        Schema::create('moderation_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('queue_item_id')->constrained('moderation_queue')->cascadeOnDelete();
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason_category', 100)->notNull();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('queue_item_id', 'idx_moderation_reports_queue');
        });

        Schema::create('moderation_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('queue_item_id')->constrained('moderation_queue')->cascadeOnDelete();
            $table->foreignId('moderator_id')->constrained('users')->cascadeOnDelete();
            $table->enum('action', ['approved', 'rejected', 'warned', 'hidden', 'deleted', 'escalated'])->notNull();
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('queue_item_id', 'idx_moderation_actions_queue');
            $table->index('moderator_id', 'idx_moderation_actions_moderator');
        });

        Schema::create('moderation_blocklist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('block_type', ['ip', 'email', 'domain', 'keyword', 'pattern', 'phone'])->notNull();
            $table->string('value', 500)->notNull();
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('block_type', 'idx_moderation_blocklist_type');
            $table->index('value', 'idx_moderation_blocklist_value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moderation_blocklist');
        Schema::dropIfExists('moderation_actions');
        Schema::dropIfExists('moderation_reports');
        Schema::dropIfExists('moderation_queue');
    }
};
