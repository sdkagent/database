<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;
use App\Models\Production\ProductionMaterialIssue;

#[UseFactory]
class StockItem extends Model
{
    use HasFactory;

    protected $table = 'stock_items';

    protected $fillable = [
        'product_id',
        'warehouse_location_id',
        'serial_number',
        'batch_number',
        'quantity',
        'reserved_quantity',
        'unit_cost',
        'expiry_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity'          => 'decimal:2',
            'reserved_quantity' => 'decimal:2',
            'unit_cost'         => 'decimal:2',
            'expiry_date'       => 'date',
            'status'            => 'string',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouseLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id');
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'stock_item_id');
    }

    public function transferOrderItems(): HasMany
    {
        return $this->hasMany(TransferOrderItem::class, 'stock_item_id');
    }

    public function productionMaterialIssues(): HasMany
    {
        return $this->hasMany(ProductionMaterialIssue::class, 'stock_item_id');
    }
}
