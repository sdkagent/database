<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class CapacityPlan extends Model
{
    use HasFactory;

    protected $table = 'capacity_plans';

    protected $fillable = [
        'work_center_id',
        'plan_date',
        'planned_hours',
        'actual_hours',
        'available_hours',
        'load_percentage',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'plan_date'       => 'date',
            'planned_hours'   => 'decimal:2',
            'actual_hours'    => 'decimal:2',
            'available_hours' => 'decimal:2',
            'load_percentage' => 'decimal:2',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class, 'work_center_id');
    }
}
