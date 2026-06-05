<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;

#[UseFactory]
class StockCountItem extends Model
{
    use HasFactory;

    protected $table = 'stock_count_items';

    public $timestamps = false;

    protected $fillable = [
        'stock_count_id',
        'product_id',
        'location_id',
        'expected_qty',
        'counted_qty',
        'difference',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'expected_qty' => 'decimal:2',
            'counted_qty'  => 'decimal:2',
            'difference'   => 'decimal:2',
            'created_at'   => 'datetime',
        ];
    }

    public function stockCount(): BelongsTo
    {
        return $this->belongsTo(StockCount::class, 'stock_count_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class);
    }
}
