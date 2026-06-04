<?php

namespace App\Models\Llm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class BotConfig extends Model
{
    use HasFactory;

    protected $table = 'bot_configs';

    protected $fillable = [
        'name', 'platform', 'platform_token', 'platform_username',
        'webhook_url', 'llm_provider_id', 'llm_system_prompt',
        'welcome_message', 'status', 'allowed_user_ids',
        'rate_limit_per_minute', 'max_conversation_length', 'settings',
    ];

    protected function casts(): array
    {
        return [
            'allowed_user_ids'        => 'json',
            'settings'                => 'json',
            'rate_limit_per_minute'   => 'integer',
            'max_conversation_length' => 'integer',
            'created_at'              => 'datetime',
            'updated_at'              => 'datetime',
        ];
    }

    public function llmProvider(): BelongsTo
    {
        return $this->belongsTo(LlmProvider::class, 'llm_provider_id');
    }
}
