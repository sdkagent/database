<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class PostMedium extends Model
{
    use HasFactory;

    protected $table = 'post_media';

    protected $fillable = [
        'post_id', 'file_name', 'file_path', 'file_type',
        'file_size', 'is_featured', 'sort_order',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'file_size'   => 'integer',
            'is_featured' => 'boolean',
            'sort_order'  => 'integer',
            'created_at'  => 'datetime',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
