<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;
use App\Models\Product\SubscriptionPlan;

#[UseFactory]
class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    public $timestamps = false;

    protected $touches = ['order'];

    protected $fillable = [
        'order_id', 'product_id', 'plan_id', 'item_type', 'name',
        'quantity', 'unit_price', 'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'quantity'   => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal'   => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function metadata(): HasMany
    {
        return $this->hasMany(OrderItemMetadata::class);
    }

    public function fileDownloads(): HasMany
    {
        return $this->hasMany(FileDownload::class);
    }
}
