<?php

namespace App\Models\Pricing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;

#[UseFactory]
class PriceTier extends Model
{
    use HasFactory;

    protected $table = 'price_tiers';

    protected $fillable = [
        'product_id', 'min_quantity', 'max_quantity', 'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'min_quantity' => 'integer',
            'max_quantity' => 'integer',
            'unit_price'   => 'decimal:2',
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
