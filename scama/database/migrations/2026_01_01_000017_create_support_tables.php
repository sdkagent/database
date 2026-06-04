<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject', 255)->nullable();
            $table->string('status', 20)->default('open');
            $table->string('priority', 10)->default('medium');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category', 100)->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index('user_id', 'idx_tickets_user');
            $table->index('assigned_to', 'idx_tickets_assigned');
            $table->index('status', 'idx_tickets_status');
            $table->index(['user_id', 'status'], 'idx_tickets_user_status');
            $table->index(['status', 'priority'], 'idx_tickets_status_priority');
            $table->index(['status', 'created_at'], 'idx_tickets_status_created');
        });

        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('message')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('ticket_id', 'idx_ticket_messages_ticket');
            $table->index('sender_id', 'idx_ticket_messages_sender');
        });

        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status', 10)->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('user_id', 'idx_chat_sessions_user');
            $table->index('assigned_to', 'idx_chat_sessions_assigned');
            $table->index(['user_id', 'status'], 'idx_chat_sessions_user_status');
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('session_id')->constrained('chat_sessions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->boolean('is_agent')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index('session_id', 'idx_chat_messages_session');
            $table->index('user_id', 'idx_chat_messages_user');
        });

        Schema::create('bot_conversations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('bot_config_id')->nullable();
            $table->text('message');
            $table->text('response')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id', 'idx_bot_conversations_user');
            $table->index('bot_config_id', 'idx_bot_conversations_bot_config');
            $table->index('created_at', 'idx_bot_conversations_created');
            $table->index(['user_id', 'created_at'], 'idx_bot_conversations_user_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bot_conversations');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
    }
};
