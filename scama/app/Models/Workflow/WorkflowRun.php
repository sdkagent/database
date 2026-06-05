<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class WorkflowRun extends Model
{
    use HasFactory;

    protected $table = 'workflow_runs';

    protected $fillable = [
        'workflow_id',
        'triggered_by',
        'trigger_type',
        'trigger_payload',
        'status',
        'current_node_id',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'trigger_payload' => 'json',
            'status'          => 'string',
            'started_at'      => 'datetime',
            'completed_at'    => 'datetime',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class);
    }

    public function triggeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function currentNode(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class, 'current_node_id');
    }

    public function workflowRunLogs(): HasMany
    {
        return $this->hasMany(WorkflowRunLog::class, 'run_id');
    }

    public function workflowRunNodeStates(): HasMany
    {
        return $this->hasMany(WorkflowRunNodeState::class, 'run_id');
    }

    public function workflowRunVariables(): HasMany
    {
        return $this->hasMany(WorkflowRunVariable::class, 'run_id');
    }

    public function approvalRequests(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class, 'workflow_run_id');
    }
}
