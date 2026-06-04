<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class TriggerWorkflowMapping extends Model
{
    use HasFactory;

    protected $table = 'trigger_workflow_mappings';

    public $timestamps = false;

    protected $fillable = [
        'trigger_id',
        'workflow_id',
        'priority',
        'conditions',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'priority'   => 'integer',
            'conditions' => 'json',
            'status'     => 'string',
            'created_at' => 'datetime',
        ];
    }

    public function trigger(): BelongsTo
    {
        return $this->belongsTo(Trigger::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class);
    }
}
