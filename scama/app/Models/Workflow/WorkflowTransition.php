<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class WorkflowTransition extends Model
{
    use HasFactory;

    protected $table = 'workflow_transitions';

    public $timestamps = false;

    protected $fillable = [
        'workflow_id',
        'from_node_id',
        'to_node_id',
        'condition_expression',
        'label',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'condition_expression' => 'json',
            'priority'             => 'integer',
            'created_at'           => 'datetime',
        ];
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class);
    }

    public function fromNode(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class, 'from_node_id');
    }

    public function toNode(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class, 'to_node_id');
    }
}
