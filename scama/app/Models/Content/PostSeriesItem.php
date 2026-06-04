<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class PostSeriesItem extends Model
{
    use HasFactory;

    protected $table = 'post_series_items';

    protected $fillable = [
        'series_id', 'post_id', 'part_order', 'part_title',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'part_order' => 'integer',
        ];
    }

    public function series(): BelongsTo
    {
        return $this->belongsTo(PostSeries::class, 'series_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
