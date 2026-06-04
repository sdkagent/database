<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Production\ProductionMaterialIssue;
use App\Models\Production\ProductionOutput;
use App\Models\Procurement\PurchaseOrderItem;
use App\Models\Procurement\PurchaseReceiptItem;

#[UseFactory]
class WarehouseLocation extends Model
{
    use HasFactory;

    protected $table = 'warehouse_locations';

    protected $fillable = [
        'warehouse_id',
        'parent_id',
        'code',
        'name',
        'type',
        'max_weight',
        'max_volume',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type'       => 'string',
            'max_weight' => 'decimal:2',
            'max_volume' => 'decimal:2',
            'is_active'  => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class);
    }

    public function warehouseLocations(): HasMany
    {
        return $this->hasMany(WarehouseLocation::class, 'parent_id');
    }

    public function stockItems(): HasMany
    {
        return $this->hasMany(StockItem::class, 'warehouse_location_id');
    }

    public function inventoryMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'from_location_id');
    }

    public function inventoryAdjustments(): HasMany
    {
        return $this->hasMany(InventoryAdjustment::class, 'warehouse_location_id');
    }

    public function stockCountItems(): HasMany
    {
        return $this->hasMany(StockCountItem::class, 'location_id');
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class, 'warehouse_location_id');
    }

    public function purchaseReceiptItems(): HasMany
    {
        return $this->hasMany(PurchaseReceiptItem::class, 'warehouse_location_id');
    }

    public function productionOutputs(): HasMany
    {
        return $this->hasMany(ProductionOutput::class, 'warehouse_location_id');
    }

    public function productionMaterialIssues(): HasMany
    {
        return $this->hasMany(ProductionMaterialIssue::class, 'warehouse_location_id');
    }
}
