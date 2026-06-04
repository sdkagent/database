<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Licensing\License;

#[UseFactory]
class OrderItemMetadata extends Model
{
    use HasFactory;

    protected $table = 'order_item_metadata';

    protected $fillable = [
        'order_item_id', 'license_id', 'meta_key', 'meta_value',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
}
