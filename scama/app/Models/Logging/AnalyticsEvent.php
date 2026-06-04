<?php

namespace App\Models\Logging;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Cms\CmsPage;
use App\Models\Content\Post;
use App\Models\Auth\User;

#[UseFactory]
class AnalyticsEvent extends Model
{
    use HasFactory;

    protected $table = 'analytics_events';

    protected $fillable = [
        'event_type', 'page_url', 'referrer_url', 'utm_source',
        'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
        'user_agent', 'ip_address', 'session_id', 'user_id',
        'post_id', 'page_id',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'page_id');
    }
}
