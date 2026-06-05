<?php

namespace App\Models\Sms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class SmsAutomation extends Model
{
    use HasFactory;

    protected $table = 'sms_automations';

    protected $fillable = [
        'name',
        'trigger_type',
        'event_name',
        'event_conditions',
        'cron_expression',
        'timezone',
        'target_type',
        'target_roles',
        'filter_criteria',
        'message_body',
        'sms_template_id',
        'provider_id',
        'is_active',
        'last_triggered_at',
        'total_sent',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'trigger_type'      => 'string',
            'event_conditions'  => 'json',
            'target_type'       => 'string',
            'target_roles'      => 'json',
            'filter_criteria'   => 'json',
            'is_active'         => 'boolean',
            'last_triggered_at' => 'datetime',
            'total_sent'        => 'integer',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
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
}
