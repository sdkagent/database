<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;

#[UseFactory]
class TransferOrderItem extends Model
{
    use HasFactory;

    protected $table = 'transfer_order_items';

    public $timestamps = false;

    protected $fillable = [
        'transfer_order_id',
        'product_id',
        'stock_item_id',
        'quantity',
        'received_qty',
        'unit_cost',
    ];

    protected function casts(): array
    {
        return [
            'quantity'     => 'decimal:2',
            'received_qty' => 'decimal:2',
            'unit_cost'    => 'decimal:2',
            'created_at'   => 'datetime',
        ];
    }

    public function transferOrder(): BelongsTo
    {
        return $this->belongsTo(TransferOrder::class, 'transfer_order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function stockItem(): BelongsTo
    {
        return $this->belongsTo(StockItem::class, 'stock_item_id');
    }
}
