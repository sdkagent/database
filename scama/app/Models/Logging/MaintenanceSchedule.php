<?php

namespace App\Models\Logging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Production\WorkCenter;

#[UseFactory]
class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $table = 'maintenance_schedules';

    protected $fillable = [
        'work_center_id',
        'title',
        'type',
        'frequency',
        'frequency_value',
        'last_done_at',
        'next_due_at',
        'estimated_hours',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type'            => 'string',
            'frequency'       => 'string',
            'frequency_value' => 'integer',
            'last_done_at'    => 'datetime',
            'next_due_at'     => 'datetime',
            'estimated_hours' => 'decimal:2',
            'is_active'       => 'boolean',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class, 'work_center_id');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'maintenance_schedule_id');
    }
}
