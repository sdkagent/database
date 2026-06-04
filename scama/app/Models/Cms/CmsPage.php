<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class CmsPage extends Model
{
    use HasFactory;

    protected $table = 'cms_pages';

    protected $fillable = [
        'title', 'slug', 'content', 'status', 'meta_title',
        'meta_description', 'meta_keywords', 'canonical_url',
        'og_image', 'og_title', 'og_description', 'twitter_card',
        'noindex', 'priority', 'changefreq', 'sitemap_include',
        'published_at', 'scheduled_for', 'author_id',
    ];

    protected function casts(): array
    {
        return [
            'noindex'         => 'boolean',
            'sitemap_include' => 'boolean',
            'published_at'    => 'datetime',
            'scheduled_for'   => 'datetime',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(CmsTag::class, 'cms_page_tags');
    }
}
