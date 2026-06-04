<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use App\Models\Auth\User;

#[UseFactory]
class PostView extends Model
{
    use HasFactory;

    protected $table = 'post_views';

    protected $fillable = [
        'post_id', 'user_id', 'ip_address', 'user_agent',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'viewed_at'  => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
