<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;
use App\Models\Inventory\StockItem;
use App\Models\Inventory\WarehouseLocation;

#[UseFactory]
class ProductionMaterialIssue extends Model
{
    use HasFactory;

    protected $table = 'production_material_issues';

    public $timestamps = false;

    protected $fillable = [
        'production_order_id',
        'stock_item_id',
        'product_id',
        'warehouse_location_id',
        'quantity',
        'unit_cost',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity'  => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'issued_at' => 'datetime',
        ];
    }

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class, 'production_order_id');
    }

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class, 'stock_item_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouseLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'warehouse_location_id');
    }
}
