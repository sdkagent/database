<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class VideoTag extends Model
{
    protected $table = 'video_tags';

    protected $fillable = ['video_id', 'tag_id'];

    public $timestamps = false;

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(CmsTag::class, 'tag_id');
    }
}
