<?php

namespace App\Models\Licensing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class HardwareActivation extends Model
{
    use HasFactory;

    protected $table = 'hardware_activations';

    protected $fillable = [
        'license_id', 'machine_id', 'cpu_id', 'motherboard_serial',
        'bios_serial', 'disk_serial', 'mac_address', 'os_name', 'os_version',
        'os_architecture', 'cpu_name', 'cpu_cores', 'total_memory',
        'system_manufacturer', 'system_model', 'local_ip', 'public_ip',
        'status', 'activation_limit', 'activated_at', 'last_ping_at',
        'required_os_min', 'required_memory_mb', 'required_disk_mb',
        'compatibility_status',
    ];

    protected function casts(): array
    {
        return [
            'cpu_cores'           => 'integer',
            'total_memory'        => 'integer',
            'activation_limit'    => 'integer',
            'required_memory_mb'  => 'integer',
            'required_disk_mb'    => 'integer',
            'activated_at'        => 'datetime',
            'last_ping_at'        => 'datetime',
            'created_at'          => 'datetime',
            'updated_at'          => 'datetime',
        ];
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(HardwareActivationLog::class, 'hardware_activation_id');
    }
}
