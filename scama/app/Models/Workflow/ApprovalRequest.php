<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ApprovalRequest extends Model
{
    use HasFactory;

    protected $table = 'approval_requests';

    protected $fillable = [
        'workflow_run_id',
        'node_id',
        'status',
        'requested_by',
        'requested_at',
        'responded_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status'       => 'string',
            'requested_at' => 'datetime',
            'responded_at' => 'datetime',
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
        ];
    }

    public function workflowRun(): BelongsTo
    {
        return $this->belongsTo(WorkflowRun::class, 'workflow_run_id');
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvalStages(): HasMany
    {
        return $this->hasMany(ApprovalStage::class, 'approval_request_id');
    }
}
