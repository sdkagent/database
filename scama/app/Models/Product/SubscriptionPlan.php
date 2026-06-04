<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Commerce\CartItem;
use App\Models\Commerce\OrderItem;

#[UseFactory]
class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $table = 'subscription_plans';

    protected $fillable = [
        'name', 'code', 'duration_months', 'max_activations',
        'price_monthly', 'price_yearly', 'features', 'status',
    ];

    protected function casts(): array
    {
        return [
            'duration_months' => 'integer',
            'max_activations' => 'integer',
            'price_monthly'   => 'decimal:2',
            'price_yearly'    => 'decimal:2',
            'features'        => 'array',
            'status'          => 'boolean',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

    public function userSubscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'plan_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'plan_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'plan_id');
    }
}
