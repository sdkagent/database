<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;
use App\Models\Auth\User;

#[UseFactory]
class InventoryMovement extends Model
{
    use HasFactory;

    protected $table = 'inventory_movements';

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'from_location_id',
        'to_location_id',
        'stock_item_id',
        'movement_type',
        'reference_type',
        'reference_id',
        'quantity',
        'unit_cost',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'movement_type' => 'string',
            'quantity'      => 'decimal:2',
            'unit_cost'     => 'decimal:2',
            'created_at'    => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'from_location_id');
    }

    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'to_location_id');
    }

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class, 'stock_item_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
