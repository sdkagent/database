<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Production\ProductionOrder;

#[UseFactory]
class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';

    protected $fillable = ['name', 'code', 'address', 'city', 'state', 'country', 'postal_code', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function warehouseLocations(): HasMany
    {
        return $this->hasMany(WarehouseLocation::class);
    }

    public function stockCounts(): HasMany
    {
        return $this->hasMany(StockCount::class);
    }

    public function reorderRules(): HasMany
    {
        return $this->hasMany(ReorderRule::class);
    }

    public function transferOrders(): HasMany
    {
        return $this->hasMany(TransferOrder::class);
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class);
    }

}
