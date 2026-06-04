<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';

    protected $fillable = [
        'gallery_id', 'title', 'slug', 'description', 'video_url',
        'embed_url', 'thumbnail', 'duration', 'file_size', 'file_type',
        'author_id', 'status', 'featured', 'view_count', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'duration'   => 'integer',
            'file_size'  => 'integer',
            'view_count' => 'integer',
            'featured'   => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(VideoGallery::class, 'gallery_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(CmsTag::class, 'video_tags');
    }

    public function guidelines(): HasMany
    {
        return $this->hasMany(VideoGuideline::class);
    }
}
