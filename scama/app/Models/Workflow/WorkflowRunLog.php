<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class WorkflowRunLog extends Model
{
    use HasFactory;

    protected $table = 'workflow_run_logs';

    public $timestamps = false;

    protected $fillable = [
        'run_id',
        'node_id',
        'action_type',
        'level',
        'message',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'level'      => 'string',
            'payload'    => 'json',
            'created_at' => 'datetime',
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
