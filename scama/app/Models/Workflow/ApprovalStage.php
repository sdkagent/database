<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class ApprovalStage extends Model
{
    use HasFactory;

    protected $table = 'approval_stages';

    protected $fillable = [
        'approval_request_id',
        'stage_order',
        'status',
        'strategy',
        'min_approvers',
    ];

    protected function casts(): array
    {
        return [
            'status'     => 'string',
            'strategy'   => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function approvalRequest(): BelongsTo
    {
        return $this->belongsTo(ApprovalRequest::class, 'approval_request_id');
    }

    public function approvalAssignees(): HasMany
    {
        return $this->hasMany(ApprovalAssignee::class, 'stage_id');
    }
}
