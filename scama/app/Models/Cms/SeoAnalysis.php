<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class SeoAnalysis extends Model
{
    use HasFactory;

    protected $table = 'seo_analysis';

    protected $fillable = [
        'content_type', 'content_id', 'score', 'issues',
        'word_count', 'readability_score', 'checked_at',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'issues'           => 'json',
            'checked_at'       => 'datetime',
        ];
    }
}
