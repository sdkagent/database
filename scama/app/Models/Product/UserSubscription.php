<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Billing\Invoice;
use App\Models\Licensing\License;
use App\Models\Auth\User;

#[UseFactory]
class UserSubscription extends Model
{
    use HasFactory;

    protected $table = 'user_subscriptions';

    protected $fillable = [
        'user_id', 'plan_id', 'product_id', 'status', 'start_date',
        'end_date', 'trial_ends_at', 'stripe_subscription_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date'    => 'datetime',
            'end_date'      => 'datetime',
            'trial_ends_at' => 'datetime',
            'created_at'    => 'datetime',
            'updated_at'    => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
