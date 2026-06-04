<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Cms\CmsTag;

#[UseFactory]
class PostTag extends Model
{
    protected $table = 'post_tags';

    protected $fillable = ['post_id', 'tag_id'];

    public $timestamps = false;

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(CmsTag::class, 'tag_id');
    }
}
