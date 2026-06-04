<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;

#[UseFactory]
class ContentBlock extends Model
{
    use HasFactory;

    protected $table = 'content_blocks';

    protected $fillable = [
        'key', 'title', 'content', 'type', 'locations', 'active',
    ];

    protected function casts(): array
    {
        return [
            'locations'  => 'json',
            'active'     => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
