<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class RelatedPost extends Model
{
    use HasFactory;

    protected $table = 'related_posts';

    protected $fillable = [
        'post_id', 'related_post_id', 'relation_type', 'weight',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'weight'     => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function relatedPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'related_post_id');
    }
}
