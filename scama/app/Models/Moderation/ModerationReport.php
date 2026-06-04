<?php

namespace App\Models\Moderation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ModerationReport extends Model
{
    use HasFactory;

    protected $table = 'moderation_reports';

    public $timestamps = false;

    protected $fillable = [
        'queue_item_id', 'reporter_id', 'reason_category', 'description',
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

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
