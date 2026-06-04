<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class WorkflowRunNodeState extends Model
{
    use HasFactory;

    protected $table = 'workflow_run_node_states';

    protected $fillable = [
        'run_id',
        'node_id',
        'status',
        'input',
        'output',
        'attempts',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status'       => 'string',
            'input'        => 'json',
            'output'       => 'json',
            'started_at'   => 'datetime',
            'completed_at' => 'datetime',
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
        ];
    }

    public function run(): BelongsTo
    {
        return $this->belongsTo(WorkflowRun::class);
    }

    public function node(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class);
    }
}
