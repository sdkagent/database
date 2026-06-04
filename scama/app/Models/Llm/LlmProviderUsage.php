<?php

namespace App\Models\Llm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class LlmProviderUsage extends Model
{
    use HasFactory;

    protected $table = 'llm_provider_usage';

    protected $fillable = [
        'provider_id', 'user_id', 'tokens_used',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'tokens_used' => 'integer',
            'created_at'  => 'datetime',
        ];
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(LlmProvider::class, 'provider_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
