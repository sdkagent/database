<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;
use App\Models\Form\Routing;
use App\Models\Auth\User;
use App\Models\Inventory\Warehouse;

#[UseFactory]
class ProductionOrder extends Model
{
    use HasFactory;

    protected $table = 'production_orders';

    protected $fillable = [
        'product_id',
        'bom_id',
        'routing_id',
        'warehouse_id',
        'order_number',
        'quantity',
        'produced_qty',
        'scrap_qty',
        'status',
        'priority',
        'scheduled_start',
        'scheduled_end',
        'actual_start',
        'actual_end',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'quantity'        => 'decimal:2',
            'produced_qty'    => 'decimal:2',
            'scrap_qty'       => 'decimal:2',
            'status'          => 'string',
            'priority'        => 'string',
            'scheduled_start' => 'datetime',
            'scheduled_end'   => 'datetime',
            'actual_start'    => 'datetime',
            'actual_end'      => 'datetime',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function bom(): BelongsTo
    {
        return $this->belongsTo(BillOfMaterial::class);
    }

    public function routing(): BelongsTo
    {
        return $this->belongsTo(Routing::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function productionOrderSteps(): HasMany
    {
        return $this->hasMany(ProductionOrderStep::class, 'production_order_id');
    }

    public function productionOutputs(): HasMany
    {
        return $this->hasMany(ProductionOutput::class, 'production_order_id');
    }

    public function productionMaterialIssues(): HasMany
    {
        return $this->hasMany(ProductionMaterialIssue::class, 'production_order_id');
    }
}
