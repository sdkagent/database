<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Form\RoutingStep;

#[UseFactory]
class ProductionOrderStep extends Model
{
    use HasFactory;

    protected $table = 'production_order_steps';

    protected $fillable = [
        'production_order_id',
        'routing_step_id',
        'work_center_id',
        'status',
        'actual_setup_time',
        'actual_run_time',
        'completed_qty',
        'scrap_qty',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status'            => 'string',
            'actual_setup_time' => 'decimal:2',
            'actual_run_time'   => 'decimal:2',
            'completed_qty'     => 'decimal:2',
            'scrap_qty'         => 'decimal:2',
            'started_at'        => 'datetime',
            'completed_at'      => 'datetime',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
        ];
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function routingStep(): BelongsTo
    {
        return $this->belongsTo(RoutingStep::class, 'routing_step_id');
    }

    public function workCenter(): BelongsTo
    {
        return $this->belongsTo(WorkCenter::class, 'work_center_id');
    }
}
