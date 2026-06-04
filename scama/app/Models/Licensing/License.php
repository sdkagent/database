<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Security\FraudLog;
use App\Models\Commerce\OrderItemMetadata;
use App\Models\Product\Product;
use App\Models\Auth\User;
use App\Models\Product\UserSubscription;

#[UseFactory]
class License extends Model
{
    use HasFactory;

    protected $table = 'licenses';

    protected $fillable = [
        'user_id', 'product_id', 'api_client_id', 'subscription_id',
        'license_key', 'api_key', 'status', 'expires_at',
        'max_activations', 'current_activations', 'last_activity_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at'          => 'datetime',
            'last_activity_at'    => 'datetime',
            'max_activations'     => 'integer',
            'current_activations' => 'integer',
            'created_at'          => 'datetime',
            'updated_at'          => 'datetime',
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

    public function apiClient(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(UserSubscription::class);
    }

    public function activations(): HasMany
    {
        return $this->hasMany(LicenseActivation::class);
    }

    public function hardwareActivations(): HasMany
    {
        return $this->hasMany(HardwareActivation::class);
    }

    public function hardwareActivationLogs(): HasMany
    {
        return $this->hasMany(HardwareActivationLog::class);
    }

    public function metadata(): HasMany
    {
        return $this->hasMany(OrderItemMetadata::class);
    }

    public function fraudLogs(): HasMany
    {
        return $this->hasMany(FraudLog::class);
    }
}
