<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ApprovalAssignee extends Model
{
    use HasFactory;

    protected $table = 'approval_assignees';

    public $timestamps = false;

    protected $fillable = [
        'stage_id',
        'user_id',
        'status',
        'response',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'status'       => 'string',
            'responded_at' => 'datetime',
            'created_at'   => 'datetime',
        ];
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(ApprovalStage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
