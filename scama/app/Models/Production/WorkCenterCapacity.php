<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class WorkCenterCapacity extends Model
{
    use HasFactory;

    protected $table = 'work_center_capacity';

    protected $fillable = [
        'work_center_id',
        'capacity_date',
        'available_hours',
        'maintenance_hours',
        'booked_hours',
        'overtime_hours',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'capacity_date'     => 'date',
            'available_hours'   => 'decimal:2',
            'maintenance_hours' => 'decimal:2',
            'booked_hours'      => 'decimal:2',
            'overtime_hours'    => 'decimal:2',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
        ];
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class, 'work_center_id');
    }
}
