<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';

    public $timestamps = false;

    protected $fillable = [
        'session_id', 'user_id', 'message', 'is_agent',
    ];

    protected function casts(): array
    {
        return [
            'is_agent'   => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class, 'session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
