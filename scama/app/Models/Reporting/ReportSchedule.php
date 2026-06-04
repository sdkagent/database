<?php

namespace App\Models\Reporting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ReportSchedule extends Model
{
    use HasFactory;

    protected $table = 'report_schedules';

    protected $fillable = [
        'report_id', 'name', 'cron_expression', 'recipients', 'format',
        'config', 'last_run_at', 'next_run_at', 'status', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'recipients'  => 'array',
            'config'      => 'array',
            'last_run_at' => 'datetime',
            'next_run_at' => 'datetime',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(ReportDefinition::class, 'report_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
