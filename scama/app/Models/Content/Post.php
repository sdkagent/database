<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Cms\CmsCategory;
use App\Models\Cms\CmsTag;
use App\Models\Auth\User;

#[UseFactory]
class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'type', 'author_id', 'title', 'slug', 'content', 'excerpt',
        'featured_image', 'category_id', 'status', 'published_at',
        'scheduled_for', 'meta_title', 'meta_description', 'meta_keywords',
        'canonical_url', 'view_count', 'og_image', 'og_title', 'og_description',
        'twitter_card', 'noindex', 'priority', 'changefreq', 'sitemap_include',
    ];

    protected function casts(): array
    {
        return [
            'published_at'     => 'datetime',
            'scheduled_for'    => 'datetime',
            'view_count'       => 'integer',
            'noindex'          => 'boolean',
            'sitemap_include'  => 'boolean',
            'created_at'       => 'datetime',
            'updated_at'       => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CmsCategory::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(CmsTag::class, 'post_tags');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(PostMedium::class);
    }

    public function seriesItems(): HasMany
    {
        return $this->hasMany(PostSeriesItem::class);
    }

    public function relatedPosts(): HasMany
    {
        return $this->hasMany(RelatedPost::class, 'post_id');
    }
}
