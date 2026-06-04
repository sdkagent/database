<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class SlugHistory extends Model
{
    use HasFactory;

    protected $table = 'slug_history';

    protected $fillable = [
        'content_type', 'content_id', 'old_slug', 'new_slug',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
