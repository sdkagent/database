<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class ProductDiscount extends Model
{
    use HasFactory;

    protected $table = 'product_discounts';

    protected $fillable = [
        'product_id', 'name', 'type', 'value', 'max_uses',
        'used_count', 'starts_at', 'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'value'      => 'decimal:2',
            'max_uses'   => 'integer',
            'used_count' => 'integer',
            'starts_at'  => 'datetime',
            'ends_at'    => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
