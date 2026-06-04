<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Licensing\License;
use App\Models\Licensing\LicenseActivation;

#[UseFactory]
class FraudLog extends Model
{
    use HasFactory;

    protected $table = 'fraud_logs';

    public $timestamps = false;

    protected $fillable = [
        'license_id', 'activation_id', 'ip', 'domain', 'reason',
        'severity', 'action_taken',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function activation(): BelongsTo
    {
        return $this->belongsTo(LicenseActivation::class, 'activation_id');
    }
}
