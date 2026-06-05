<?php

namespace App\Models\Sms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class SmsCampaign extends Model
{
    use HasFactory;

    protected $table = 'sms_campaigns';

    protected $fillable = [
        'name',
        'message_body',
        'sms_template_id',
        'provider_id',
        'target_type',
        'target_roles',
        'target_user_ids',
        'filter_criteria',
        'scheduled_at',
        'sent_at',
        'completed_at',
        'status',
        'total_recipients',
        'success_count',
        'fail_count',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'target_type'      => 'string',
            'target_roles'     => 'json',
            'target_user_ids'  => 'json',
            'filter_criteria'  => 'json',
            'scheduled_at'     => 'datetime',
            'sent_at'          => 'datetime',
            'completed_at'     => 'datetime',
            'status'           => 'string',
            'total_recipients' => 'integer',
            'success_count'    => 'integer',
            'fail_count'       => 'integer',
            'created_at'       => 'datetime',
            'updated_at'       => 'datetime',
        ];
    }

    public function smsTemplate(): BelongsTo
    {
        return $this->belongsTo(SmsTemplate::class, 'sms_template_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(SmsProvider::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function smsCampaignRecipients(): HasMany
    {
        return $this->hasMany(SmsCampaignRecipient::class, 'campaign_id');
    }

    public function smsLogs(): HasMany
    {
        return $this->hasMany(SmsLog::class, 'campaign_id');
    }
}
