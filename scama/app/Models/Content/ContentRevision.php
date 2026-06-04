<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class ContentRevision extends Model
{
    use HasFactory;

    protected $table = 'content_revisions';

    protected $fillable = [
        'content_type', 'content_id', 'title', 'content',
        'summary', 'meta', 'created_by',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'meta'       => 'json',
            'created_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
