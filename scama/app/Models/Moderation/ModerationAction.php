<?php

namespace App\Models\Moderation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ModerationAction extends Model
{
    use HasFactory;

    protected $table = 'moderation_actions';

    public $timestamps = false;

    protected $fillable = [
        'queue_item_id', 'moderator_id', 'action', 'reason',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function queueItem(): BelongsTo
    {
        return $this->belongsTo(ModerationQueue::class, 'queue_item_id');
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }
}
