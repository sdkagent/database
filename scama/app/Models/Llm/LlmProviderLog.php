<?php

namespace App\Models\Llm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class LlmProviderLog extends Model
{
    use HasFactory;

    protected $table = 'llm_provider_logs';

    protected $fillable = [
        'provider_id', 'user_id', 'prompt', 'response',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
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
