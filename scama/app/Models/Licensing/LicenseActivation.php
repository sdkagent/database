<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Security\FraudLog;
use App\Models\Security\VerificationLog;

#[UseFactory]
class LicenseActivation extends Model
{
    use HasFactory;

    protected $table = 'license_activations';

    protected $fillable = [
        'license_id', 'domain', 'hosting_ip', 'status',
        'last_verified_at', 'meta',
    ];

    protected function casts(): array
    {
        return [
            'last_verified_at' => 'datetime',
            'meta'             => 'array',
            'created_at'       => 'datetime',
            'updated_at'       => 'datetime',
        ];
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function verificationLogs(): HasMany
    {
        return $this->hasMany(VerificationLog::class, 'activation_id');
    }

    public function fraudLogs(): HasMany
    {
        return $this->hasMany(FraudLog::class, 'activation_id');
    }
}
