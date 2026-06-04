<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class SellerStat extends Model
{
    use HasFactory;

    protected $table = 'seller_stats';

    public $timestamps = false;

    protected $fillable = [
        'seller_id', 'period_type', 'period_date', 'total_sales',
        'total_earnings', 'total_orders', 'total_products', 'avg_rating',
        'review_count',
    ];

    protected function casts(): array
    {
        return [
            'total_sales'    => 'decimal:2',
            'total_earnings' => 'decimal:2',
            'total_orders'   => 'integer',
            'total_products' => 'integer',
            'avg_rating'     => 'decimal:2',
            'review_count'   => 'integer',
            'period_date'    => 'date',
            'created_at'     => 'datetime',
        ];
    }

    public function sellerProfile(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class, 'seller_id');
    }
}
