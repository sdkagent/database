<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_definitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->notNull();
            $table->string('slug', 255)->notNull()->unique();
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();
            $table->enum('status', ['draft', 'active', 'paused', 'archived'])->default('draft');
            $table->unsignedInteger('version')->default(1);
            $table->json('config')->nullable();
            $table->boolean('is_system')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status', 'idx_workflow_definitions_status');
            $table->index('category', 'idx_workflow_definitions_category');
            $table->index('slug', 'idx_workflow_definitions_slug');
        });

        Schema::create('workflow_nodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('workflow_id')->constrained('workflow_definitions')->cascadeOnDelete();
            $table->enum('type', ['trigger', 'action', 'condition', 'approval', 'wait', 'gateway', 'end'])->notNull();
            $table->string('name', 255)->notNull();
            $table->text('description')->nullable();
            $table->json('config')->nullable();
            $table->integer('position_x')->default(0);
            $table->integer('position_y')->default(0);
            $table->unsignedInteger('timeout_seconds')->nullable();
            $table->unsignedInteger('retry_count')->default(0);
            $table->unsignedInteger('retry_delay')->default(0);
            $table->timestamps();

            $table->index('workflow_id', 'idx_workflow_nodes_workflow');
            $table->index('type', 'idx_workflow_nodes_type');
        });

        Schema::create('workflow_transitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('workflow_id')->constrained('workflow_definitions')->cascadeOnDelete();
            $table->foreignId('from_node_id')->constrained('workflow_nodes')->cascadeOnDelete();
            $table->foreignId('to_node_id')->constrained('workflow_nodes')->cascadeOnDelete();
            $table->json('condition_expression')->nullable();
            $table->string('label', 255)->nullable();
            $table->integer('priority')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('workflow_id', 'idx_wf_transitions_workflow');
            $table->index('from_node_id', 'idx_wf_transitions_from');
            $table->index('to_node_id', 'idx_wf_transitions_to');
        });

        Schema::create('workflow_runs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('workflow_id')->constrained('workflow_definitions')->cascadeOnDelete();
            $table->foreignId('triggered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('trigger_type', 100)->nullable();
            $table->json('trigger_payload')->nullable();
            $table->enum('status', ['running', 'completed', 'failed', 'cancelled', 'paused'])->default('running');
            $table->foreignId('current_node_id')->nullable()->constrained('workflow_nodes')->nullOnDelete();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('workflow_id', 'idx_workflow_runs_workflow');
            $table->index('status', 'idx_workflow_runs_status');
            $table->index('triggered_by', 'idx_workflow_runs_triggered_by');
        });

        Schema::create('workflow_run_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('run_id')->constrained('workflow_runs')->cascadeOnDelete();
            $table->foreignId('node_id')->nullable()->constrained('workflow_nodes')->nullOnDelete();
            $table->string('action_type', 100)->nullable();
            $table->enum('level', ['info', 'warn', 'error', 'debug'])->default('info');
            $table->text('message')->notNull();
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('run_id', 'idx_wf_run_logs_run');
            $table->index('node_id', 'idx_wf_run_logs_node');
        });

        Schema::create('workflow_run_node_states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('run_id')->constrained('workflow_runs')->cascadeOnDelete();
            $table->foreignId('node_id')->constrained('workflow_nodes')->cascadeOnDelete();
            $table->enum('status', ['pending', 'running', 'completed', 'failed', 'skipped', 'retrying'])->default('pending');
            $table->json('input')->nullable();
            $table->json('output')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('run_id', 'idx_wf_run_node_states_run');
            $table->index('node_id', 'idx_wf_run_node_states_node');
            $table->index('status', 'idx_wf_run_node_states_status');
        });

        Schema::create('workflow_run_variables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('run_id')->constrained('workflow_runs')->cascadeOnDelete();
            $table->string('name', 255)->notNull();
            $table->json('value')->nullable();
            $table->timestamps();

            $table->unique(['run_id', 'name'], 'idx_wf_run_vars_name');
            $table->index('run_id', 'idx_wf_run_vars_run');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_run_variables');
        Schema::dropIfExists('workflow_run_node_states');
        Schema::dropIfExists('workflow_run_logs');
        Schema::dropIfExists('workflow_runs');
        Schema::dropIfExists('workflow_transitions');
        Schema::dropIfExists('workflow_nodes');
        Schema::dropIfExists('workflow_definitions');
    }
};
