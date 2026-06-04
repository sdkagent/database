<?php

namespace App\Models\Llm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class RateLimitRule extends Model
{
    use HasFactory;

    protected $table = 'rate_limit_rules';

    protected $fillable = [
        'name', 'route_pattern', 'http_method', 'max_requests',
        'window_seconds', 'response_code', 'response_message', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'max_requests'   => 'integer',
            'window_seconds' => 'integer',
            'response_code'  => 'integer',
            'is_active'      => 'boolean',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
        ];
    }

    public function logs(): HasMany
    {
        return $this->hasMany(RateLimitLog::class, 'rule_id');
    }
}
