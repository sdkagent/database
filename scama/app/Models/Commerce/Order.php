<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Licensing\ApiClient;
use App\Models\Billing\Invoice;
use App\Models\Billing\Refund;
use App\Models\Auth\User;
use App\Models\Auth\UserAddress;

#[UseFactory]
class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $with = ['items', 'billingAddress', 'shippingAddress'];

    protected $fillable = [
        'user_id', 'order_number', 'status', 'subtotal', 'tax',
        'discount_total', 'total', 'currency', 'notes', 'billing_address_id',
        'shipping_address_id', 'coupon_id', 'api_client_id', 'customer_notes',
        'ip_address', 'user_agent', 'paid_at', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'       => 'decimal:2',
            'tax'            => 'decimal:2',
            'discount_total' => 'decimal:2',
            'total'          => 'decimal:2',
            'paid_at'        => 'datetime',
            'cancelled_at'   => 'datetime',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function apiClient(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class);
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'billing_address_id');
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'shipping_address_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }
}
