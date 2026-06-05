<?php

namespace App\Models\Logging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;
use App\Models\Production\WorkCenter;

#[UseFactory]
class MaintenanceLog extends Model
{
    use HasFactory;

    protected $table = 'maintenance_logs';

    protected $fillable = [
        'maintenance_schedule_id',
        'work_center_id',
        'title',
        'description',
        'type',
        'status',
        'started_at',
        'completed_at',
        'duration_hours',
        'cost',
        'performed_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type'           => 'string',
            'status'         => 'string',
            'started_at'     => 'datetime',
            'completed_at'   => 'datetime',
            'duration_hours' => 'decimal:2',
            'cost'           => 'decimal:2',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    public function maintenanceSchedule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class, 'maintenance_schedule_id');
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class, 'work_center_id');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
