<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Logging\MaintenanceLog;
use App\Models\Logging\MaintenanceSchedule;
use App\Models\Form\RoutingStep;

#[UseFactory]
class WorkCenter extends Model
{
    use HasFactory;

    protected $table = 'work_centers';

    protected $fillable = [
        'code',
        'name',
        'type',
        'description',
        'cost_per_hour',
        'efficiency_rate',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type'            => 'string',
            'cost_per_hour'   => 'decimal:2',
            'efficiency_rate' => 'decimal:2',
            'is_active'       => 'boolean',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

    public function workCenterCapacities(): HasMany
    {
        return $this->hasMany(WorkCenterCapacity::class, 'work_center_id');
    }

    public function routingSteps(): HasMany
    {
        return $this->hasMany(RoutingStep::class, 'work_center_id');
    }

    public function productionOrderSteps(): HasMany
    {
        return $this->hasMany(ProductionOrderStep::class, 'work_center_id');
    }

    public function capacityPlans(): HasMany
    {
        return $this->hasMany(CapacityPlan::class, 'work_center_id');
    }

    public function maintenanceSchedules(): HasMany
    {
        return $this->hasMany(MaintenanceSchedule::class, 'work_center_id');
    }

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'work_center_id');
    }
}
