<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class HardwareActivationLog extends Model
{
    use HasFactory;

    protected $table = 'hardware_activation_logs';

    public $timestamps = false;

    protected $fillable = [
        'hardware_activation_id', 'license_id', 'machine_id',
        'hardware_snapshot', 'system_specs', 'activation_status',
        'failure_reason', 'vm_detected', 'tamper_detected',
        'compatibility_result',
    ];

    protected function casts(): array
    {
        return [
            'hardware_snapshot' => 'array',
            'system_specs'      => 'array',
            'vm_detected'       => 'boolean',
            'tamper_detected'   => 'boolean',
            'created_at'        => 'datetime',
        ];
    }

    public function hardwareActivation(): BelongsTo
    {
        return $this->belongsTo(HardwareActivation::class);
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }
}
