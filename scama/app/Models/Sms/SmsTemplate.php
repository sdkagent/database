<?php

namespace App\Models\Sms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class SmsTemplate extends Model
{
    use HasFactory;

    protected $table = 'sms_templates';

    protected $fillable = [
        'name',
        'category',
        'body',
        'variables',
    ];

    protected function casts(): array
    {
        return [
            'variables'  => 'json',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function smsCampaigns(): HasMany
    {
        return $this->hasMany(SmsCampaign::class, 'sms_template_id');
    }

    public function smsAutomations(): HasMany
    {
        return $this->hasMany(SmsAutomation::class, 'sms_template_id');
    }
}
