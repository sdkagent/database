<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class UserTwoFa extends Model
{
    use HasFactory;

    protected $table = 'user_2fa';

    protected $fillable = [
        'user_id', 'secret', 'method', 'backup_codes', 'is_enabled', 'verified_at',
    ];

    protected $hidden = [
        'secret',
    ];

    protected function casts(): array
    {
        return [
            'backup_codes' => 'array',
            'is_enabled'   => 'boolean',
            'verified_at'  => 'datetime',
            'created_at'   => 'datetime',
            'updated_at'   => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
