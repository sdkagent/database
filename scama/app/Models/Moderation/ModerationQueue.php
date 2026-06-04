<?php

namespace App\Models\Moderation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ModerationQueue extends Model
{
    use HasFactory;

    protected $table = 'moderation_queue';

    protected $fillable = [
        'content_type', 'content_id', 'reported_by', 'status',
        'priority', 'assigned_to', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'content_id' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ModerationReport::class, 'queue_item_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(ModerationAction::class, 'queue_item_id');
    }
}
