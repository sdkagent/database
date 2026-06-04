<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Content\Post;

#[UseFactory]
class CmsTag extends Model
{
    use HasFactory;

    protected $table = 'cms_tags';

    protected $fillable = [
        'name', 'slug',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tags');
    }

    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'video_tags');
    }

    public function cmsPages(): BelongsToMany
    {
        return $this->belongsToMany(CmsPage::class, 'cms_page_tags');
    }
}
