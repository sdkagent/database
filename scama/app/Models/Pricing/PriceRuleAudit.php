<?php

namespace App\Models\Pricing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Commerce\Order;
use App\Models\Product\Product;
use App\Models\Auth\User;

#[UseFactory]
class PriceRuleAudit extends Model
{
    use HasFactory;

    protected $table = 'price_rule_audit';

    public $timestamps = false;

    protected $fillable = [
        'price_rule_id', 'order_id', 'user_id', 'product_id',
        'original_price', 'adjusted_price', 'rule_name', 'context',
    ];

    protected function casts(): array
    {
        return [
            'original_price' => 'decimal:2',
            'adjusted_price' => 'decimal:2',
            'context'        => 'array',
            'applied_at'     => 'datetime',
        ];
    }

    public function priceRule(): BelongsTo
    {
        return $this->belongsTo(PriceRule::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
