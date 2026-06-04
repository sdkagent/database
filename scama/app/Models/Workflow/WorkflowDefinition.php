<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class WorkflowDefinition extends Model
{
    use HasFactory;

    protected $table = 'workflow_definitions';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'status',
        'version',
        'config',
        'is_system',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'status'     => 'string',
            'config'     => 'json',
            'is_system'  => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function workflowNodes(): HasMany
    {
        return $this->hasMany(WorkflowNode::class, 'workflow_id');
    }

    public function workflowTransitions(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'workflow_id');
    }

    public function workflowRuns(): HasMany
    {
        return $this->hasMany(WorkflowRun::class, 'workflow_id');
    }

    public function triggerWorkflowMappings(): HasMany
    {
        return $this->hasMany(TriggerWorkflowMapping::class, 'workflow_id');
    }
}
