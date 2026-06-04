<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class AuthLog extends Model
{
    use HasFactory;

    protected $table = 'auth_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'type', 'ip_address', 'device_fingerprint', 'status', 'details',
    ];

    protected function casts(): array
    {
        return [
            'details'    => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
