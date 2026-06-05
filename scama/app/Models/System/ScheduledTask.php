<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ScheduledTask extends Model
{
    use HasFactory;

    protected $table = 'scheduled_tasks';

    protected $fillable = [
        'name',
        'description',
        'cron_expression',
        'task_type',
        'config',
        'status',
        'last_run_at',
        'next_run_at',
        'is_system',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'task_type'   => 'string',
            'config'      => 'json',
            'status'      => 'string',
            'last_run_at' => 'datetime',
            'next_run_at' => 'datetime',
            'is_system'   => 'boolean',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
