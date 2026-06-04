<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('triggers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->string('slug', 255)->notNull()->unique();
            $table->string('event_type', 100)->notNull();
            $table->text('description')->nullable();
            $table->json('config')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index('event_type', 'idx_triggers_event_type');
            $table->index('status', 'idx_triggers_status');
        });

        Schema::create('trigger_workflow_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('trigger_id')->constrained('triggers')->cascadeOnDelete();
            $table->foreignId('workflow_id')->constrained('workflow_definitions')->cascadeOnDelete();
            $table->integer('priority')->default(0);
            $table->json('conditions')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('created_at')->useCurrent();

            $table->index('trigger_id', 'idx_twm_trigger');
            $table->index('workflow_id', 'idx_twm_workflow');
        });

        Schema::create('event_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_type', 100)->notNull();
            $table->string('source_type', 100)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('event_type', 'idx_event_log_type');
            $table->index(['source_type', 'source_id'], 'idx_event_log_source');
            $table->index('occurred_at', 'idx_event_log_occurred');
        });

        Schema::create('condition_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->enum('operator', ['AND', 'OR'])->default('AND');
            $table->timestamps();
        });

        Schema::create('condition_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('group_id')->constrained('condition_groups')->cascadeOnDelete();
            $table->string('field', 255)->notNull();
            $table->enum('operator', ['equals', 'not_equals', 'greater_than', 'less_than', 'greater_or_equal', 'less_or_equal', 'contains', 'not_contains', 'in', 'not_in', 'starts_with', 'ends_with', 'is_empty', 'is_not_empty', 'matches'])->notNull();
            $table->json('value')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('group_id', 'idx_condition_rules_group');
        });

        Schema::create('condition_group_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('group_id')->constrained('condition_groups')->cascadeOnDelete();
            $table->string('entity_type', 100)->notNull();
            $table->unsignedBigInteger('entity_id')->notNull();
            $table->timestamp('created_at')->useCurrent();

            $table->index('group_id', 'idx_cgm_group');
            $table->index(['entity_type', 'entity_id'], 'idx_cgm_entity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('condition_group_mappings');
        Schema::dropIfExists('condition_rules');
        Schema::dropIfExists('condition_groups');
        Schema::dropIfExists('event_log');
        Schema::dropIfExists('trigger_workflow_mappings');
        Schema::dropIfExists('triggers');
    }
};
