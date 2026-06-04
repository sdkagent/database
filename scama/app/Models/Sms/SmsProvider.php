<?php

namespace App\Models\Sms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class SmsProvider extends Model
{
    use HasFactory;

    protected $table = 'sms_providers';

    protected $fillable = [
        'name',
        'provider',
        'api_key',
        'api_secret',
        'from_number',
        'api_endpoint',
        'config',
        'is_active',
        'is_default',
        'priority',
    ];

    protected $hidden = [
        'api_secret',
    ];

    protected function casts(): array
    {
        return [
            'provider'   => 'string',
            'config'     => 'json',
            'is_active'  => 'boolean',
            'is_default' => 'boolean',
            'priority'   => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function smsCampaigns(): HasMany
    {
        return $this->hasMany(SmsCampaign::class, 'provider_id');
    }

    public function smsCampaignRecipients(): HasMany
    {
        return $this->hasMany(SmsCampaignRecipient::class, 'provider_id');
    }

    public function smsAutomations(): HasMany
    {
        return $this->hasMany(SmsAutomation::class, 'provider_id');
    }

    public function smsLogs(): HasMany
    {
        return $this->hasMany(SmsLog::class, 'provider_id');
    }
}
