<?php

namespace App\Models\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Production\ProductionOrderStep;
use App\Models\Production\WorkCenter;

#[UseFactory]
class RoutingStep extends Model
{
    use HasFactory;

    protected $table = 'routing_steps';

    protected $fillable = [
        'routing_id',
        'work_center_id',
        'step_name',
        'step_order',
        'setup_time',
        'run_time',
        'teardown_time',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'step_order'    => 'integer',
            'setup_time'    => 'decimal:2',
            'run_time'      => 'decimal:2',
            'teardown_time' => 'decimal:2',
            'created_at'    => 'datetime',
            'updated_at'    => 'datetime',
        ];
    }

    public function routing(): BelongsTo
    {
        return $this->belongsTo(Routing::class);
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class, 'work_center_id');
    }

    public function productionOrderSteps(): HasMany
    {
        return $this->hasMany(ProductionOrderStep::class, 'routing_step_id');
    }
}
