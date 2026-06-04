<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class WorkflowNode extends Model
{
    use HasFactory;

    protected $table = 'workflow_nodes';

    protected $fillable = [
        'workflow_id',
        'type',
        'name',
        'description',
        'config',
        'position_x',
        'position_y',
        'timeout_seconds',
        'retry_count',
        'retry_delay',
    ];

    protected function casts(): array
    {
        return [
            'type'       => 'string',
            'config'     => 'json',
            'position_x' => 'integer',
            'position_y' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class);
    }

    public function workflowTransitions(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'from_node_id');
    }

    public function workflowRuns(): HasMany
    {
        return $this->hasMany(WorkflowRun::class, 'current_node_id');
    }

    public function workflowRunLogs(): HasMany
    {
        return $this->hasMany(WorkflowRunLog::class, 'node_id');
    }

    public function workflowRunNodeStates(): HasMany
    {
        return $this->hasMany(WorkflowRunNodeState::class, 'node_id');
    }

    public function approvalRequests(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class, 'node_id');
    }
}
