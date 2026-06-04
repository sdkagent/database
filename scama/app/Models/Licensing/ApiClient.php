<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Commerce\Order;
use App\Models\Commerce\OrderStatusHistory;
use App\Models\Auth\User;

#[UseFactory]
class ApiClient extends Model
{
    use HasFactory;

    protected $table = 'api_clients';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'name', 'api_key', 'api_secret', 'status',
        'rate_limit', 'last_used_at',
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
    ];

    protected function casts(): array
    {
        return [
            'rate_limit'   => 'integer',
            'last_used_at' => 'datetime',
            'created_at'   => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(License::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function orderStatusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
