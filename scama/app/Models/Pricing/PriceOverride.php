<?php

namespace App\Models\Pricing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Product\Product;
use App\Models\Product\SubscriptionPlan;
use App\Models\Auth\User;

#[UseFactory]
class PriceOverride extends Model
{
    use HasFactory;

    protected $table = 'price_overrides';

    protected $fillable = [
        'user_id', 'product_id', 'plan_id', 'override_price',
        'override_type', 'starts_at', 'expires_at', 'reason', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'override_price' => 'decimal:2',
            'starts_at'      => 'datetime',
            'expires_at'     => 'datetime',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
