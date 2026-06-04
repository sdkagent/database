<?php

namespace App\Models\Llm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class RateLimitLog extends Model
{
    use HasFactory;

    protected $table = 'rate_limit_logs';

    public $timestamps = false;

    protected $fillable = [
        'rule_id', 'user_id', 'ip_address', 'route', 'http_method', 'identifier',
    ];

    protected function casts(): array
    {
        return [
            'hit_at' => 'datetime',
        ];
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(RateLimitRule::class, 'rule_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
