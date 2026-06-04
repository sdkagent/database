<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class UserDevice extends Model
{
    use HasFactory;

    protected $table = 'user_devices';

    protected $fillable = [
        'user_id',
        'platform',
        'device_token',
        'device_name',
        'fingerprint',
        'ip_address',
        'user_agent',
        'is_active',
        'last_active_at',
        'last_notified_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active'        => 'boolean',
            'last_active_at'   => 'datetime',
            'last_notified_at' => 'datetime',
            'created_at'       => 'datetime',
            'updated_at'       => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
