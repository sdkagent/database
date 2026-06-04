<?php

namespace App\Models\Logging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class WebhookDeliveryLog extends Model
{
    use HasFactory;

    protected $table = 'webhook_delivery_logs';

    public $timestamps = false;

    protected $fillable = [
        'webhook_id',
        'event_type',
        'payload',
        'request_headers',
        'response_status',
        'response_body',
        'attempt',
        'success',
        'error_message',
        'delivered_at',
        'next_retry_at',
    ];

    protected function casts(): array
    {
        return [
            'payload'         => 'json',
            'request_headers' => 'json',
            'success'         => 'boolean',
            'delivered_at'    => 'datetime',
            'next_retry_at'   => 'datetime',
            'created_at'      => 'datetime',
        ];
    }

    public function webhook(): BelongsTo
    {
        return $this->belongsTo(Webhook::class);
    }
}
