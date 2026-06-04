<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Licensing\LicenseActivation;

#[UseFactory]
class VerificationLog extends Model
{
    use HasFactory;

    protected $table = 'verification_logs';

    public $timestamps = false;

    protected $hidden = [
        'license_key',
        'api_key',
    ];

    protected $fillable = [
        'activation_id', 'license_key', 'api_key', 'ip_address',
        'user_agent', 'request_domain', 'request_ip', 'tier1_api',
        'tier2_license', 'tier3_domain', 'tier4_ip', 'tier5_subscription',
        'overall_result',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function activation(): BelongsTo
    {
        return $this->belongsTo(LicenseActivation::class, 'activation_id');
    }
}
