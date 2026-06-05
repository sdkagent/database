<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class WorkflowRunVariable extends Model
{
    use HasFactory;

    protected $table = 'workflow_run_variables';

    protected $fillable = [
        'run_id',
        'name',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value'      => 'json',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function run(): BelongsTo
    {
        return $this->belongsTo(WorkflowRun::class);
    }
}
