<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;

#[UseFactory]
class ReorderRule extends Model
{
    use HasFactory;

    protected $table = 'reorder_rules';

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'min_quantity',
        'max_quantity',
        'reorder_point',
        'reorder_qty',
        'lead_time_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_quantity'   => 'decimal:2',
            'max_quantity'   => 'decimal:2',
            'reorder_point'  => 'decimal:2',
            'reorder_qty'    => 'decimal:2',
            'lead_time_days' => 'integer',
            'is_active'      => 'boolean',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
