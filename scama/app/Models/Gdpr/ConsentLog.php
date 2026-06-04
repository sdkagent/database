<?php

namespace App\Models\Gdpr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ConsentLog extends Model
{
    use HasFactory;

    protected $table = 'consent_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id', 'consent_type', 'purpose', 'granted', 'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'granted'    => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
