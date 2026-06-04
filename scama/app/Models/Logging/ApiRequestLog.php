<?php

namespace App\Models\Logging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Licensing\ApiClient;
use App\Models\Commerce\Order;

#[UseFactory]
class ApiRequestLog extends Model
{
    use HasFactory;

    protected $table = 'api_request_logs';

    protected $fillable = [
        'api_client_id', 'order_id', 'endpoint', 'method',
        'request_data', 'response_data', 'status_code', 'ip_address',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'request_data'  => 'json',
            'response_data' => 'json',
            'status_code'   => 'integer',
            'created_at'    => 'datetime',
        ];
    }

    public function apiClient(): BelongsTo
    {
        return $this->belongsTo(ApiClient::class, 'api_client_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
