<?php

namespace App\Models\Commerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Licensing\ApiClient;
use App\Models\Auth\User;

#[UseFactory]
class OrderStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'order_status_history';

    public $timestamps = false;

    protected $fillable = [
        'order_id', 'from_status', 'to_status', 'changed_by',
        'api_client_id', 'reason',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function apiClient(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class);
    }
}
