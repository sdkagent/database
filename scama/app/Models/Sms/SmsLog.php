<?php

namespace App\Models\Sms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class SmsLog extends Model
{
    use HasFactory;

    protected $table = 'sms_logs';

    public $timestamps = false;

    protected $fillable = [
        'provider_id',
        'campaign_id',
        'recipient_id',
        'direction',
        'request_payload',
        'response_payload',
        'http_status',
        'provider_message_id',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'direction'        => 'string',
            'request_payload'  => 'json',
            'response_payload' => 'json',
            'http_status'      => 'integer',
            'created_at'       => 'datetime',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(SmsProvider::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(SmsCampaign::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(SmsCampaignRecipient::class);
    }
}
