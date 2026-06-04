<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class CmsPageTag extends Model
{
    protected $table = 'cms_page_tags';

    protected $fillable = ['page_id', 'tag_id'];

    public $timestamps = false;

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'page_id');
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(CmsTag::class, 'tag_id');
    }
}
