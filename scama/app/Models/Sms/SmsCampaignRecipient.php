<?php

namespace App\Models\Sms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class SmsCampaignRecipient extends Model
{
    use HasFactory;

    protected $table = 'sms_campaign_recipients';

    public $timestamps = false;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'phone',
        'status',
        'error_message',
        'provider_message_id',
        'provider_id',
        'sent_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'status'       => 'string',
            'sent_at'      => 'datetime',
            'delivered_at' => 'datetime',
            'created_at'   => 'datetime',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(SmsCampaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(SmsProvider::class);
    }

    public function smsLogs(): HasMany
    {
        return $this->hasMany(SmsLog::class, 'recipient_id');
    }
}
