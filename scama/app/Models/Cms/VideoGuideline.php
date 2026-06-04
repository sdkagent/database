<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class VideoGuideline extends Model
{
    use HasFactory;

    protected $table = 'video_guidelines';

    protected $fillable = [
        'video_id', 'step_order', 'title', 'description',
        'time_marker', 'image',
    ];

    protected function casts(): array
    {
        return [
            'step_order'  => 'integer',
            'time_marker' => 'integer',
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
        ];
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
